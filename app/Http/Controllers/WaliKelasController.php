<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Beasiswa;
use App\Mahasiswa;
use Illuminate\Http\Request;


class WaliKelasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:waliKelas');
    }

    public function getPendaftarKelas(Request $request)
    {
        $this->validate($request, [
            'beasiswa_id' => 'required',
        ]);
        try {
            $user = Auth::User();
            $waliKelasId = $user->waliKelas->id;
            $pendaftarBeasiswa = Mahasiswa::with(['beasiswa' => function ($query) use ($request) {
                $query->where('beasiswa_id', $request->beasiswa_id)->where('status', 'Mendaftar');
            }, 'orangTuaMahasiswa', 'saudaraMahasiswa'])->get();
            $pendaftarKelas = array();
            foreach ($pendaftarBeasiswa as $key => $value) {
                if (count($value->beasiswa) != 0 && $value->wali_kelas_id == $waliKelasId) {
                    $beasiswa = $value->beasiswa;
                    unset($value->beasiswa);
                    $value->beasiswa = $beasiswa[0];
                    array_push($pendaftarKelas, $value);
                }
            }
            return $this->apiResponseGet(200, count($pendaftarKelas), $pendaftarKelas]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function updateNilaiKelayakan(Request $request)
    {
        $this->validate($request, [
            'beasiswa_id' => 'required|integer',
            'nim' => 'required|integer',
            'skor_prestasi' => 'required|integer|gte:0',
            'skor_perilaku' => 'required|integer|gte:0|lte:4',
            'skor_organisasi' => 'required|integer|gte:0',
        ]);
        try {
            $user = Auth::User();
            $waliKelasId = $user->waliKelas->id;
            $mahasiswa = Mahasiswa::where('nim', $request->nim)->firstOrFail();
            $beasiswa = Beasiswa::findOrFail('beasiswa_id');
            if ($mahasiswa->wali_kelas_id == $waliKelasId) {
                $pendaftaranMahasiswa = $mahasiswa->beasiswa()->wherePivot('beasiswa_id', $request->beasiswa_id)->where('status', 'Mendaftar')->firstOrFail();
                $pendaftaranMahasiswa->pivot->skor_prestasi = $request->skor_prestasi;
                $pendaftaranMahasiswa->pivot->skor_perilaku = $request->skor_perilaku;
                $pendaftaranMahasiswa->pivot->skor_organisasi = $request->skor_organisasi;
                $pendaftaranMahasiswa->pivot->skor_akhir = $pendaftaranMahasiswa->pivot->skor_ipk * $beasiswa->bobot_ipk / 100 +
                    $pendaftaranMahasiswa->pivot->skor_prestasi * $beasiswa->bobot_prestasi / 100 +
                    $pendaftaranMahasiswa->pivot->skor_perilaku * $beasiswa->bobot_perilaku / 100 +
                    $pendaftaranMahasiswa->pivot->skor_organisasi * $beasiswa->bobot_organisasi / 100 +
                    $pendaftaranMahasiswa->pivot->skor_kemampuan_ekonomi * $beasiswa->bobot_kemampuan_ekonomi / 100;
                $pendaftaranMahasiswa->pivot->status = "Dinilai oleh wali kelas";
                $pendaftaranMahasiswa->pivot->save();
                return $this->apiResponse(200, 'success', ['hasil_penilaian' => $pendaftaranMahasiswa->pivot]);
            }
            return $this->apiResponse(201, 'Bukan mahasiswa yang diampu wali kelas', null);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function getSertifikatMahasiswa(Request $request)
    {
        $this->validate($request, [
            'nim' => 'required',
        ]);
        try {
            $user = Auth::User();
            $waliKelasId = $user->waliKelas->id;
            $mahasiswa = Mahasiswa::where('nim', $request->nim)->where('wali_kelas_id', $waliKelasId)->firstOrFail();
            $sertifikat = array(
                'list_sertifikat_prestasi' => $mahasiswa->sertifikatPrestasi,
                'list_sertifikat_organisasi' => $mahasiswa->sertifikatOrganisasi,
            );
            return $this->apiResponse(200, 'success', ['sertifikat' => $sertifikat]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }
}
