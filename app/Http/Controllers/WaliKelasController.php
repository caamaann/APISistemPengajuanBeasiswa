<?php

namespace App\Http\Controllers;

use App\PerbandinganAlternatif;
use App\PerbandinganKriteria;
use Illuminate\Database\Eloquent\Model;
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
            return $this->apiResponseGet(200, count($pendaftarKelas), $pendaftarKelas);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function getMahasiswa(Request $request)
    {
        if (!$request->length) {
            $length = 10;
        } else {
            $length = $request->length;
        }
        if (!$request->page) {
            $page = 1;
        } else {
            $page = $request->page;
        }
        if (!$request->search_text) {
            $search_text = "";
        } else {
            $search_text = $request->search_text;
        }

        try {
            $user = Auth::User();
            $waliKelasId = $user->waliKelas->id;

            if ($request->id) {
                $mahasiswa = Mahasiswa::where('id', $request->id)->where('wali_kelas_id', $waliKelasId)->with('user', 'programStudi')->get();
                $count = 1;
            } else {
                $query = Mahasiswa::where('nama', 'like', '%' . $search_text . '%')->where('wali_kelas_id', $waliKelasId);
                $count = $query->count();
                $mahasiswa = $query->skip(($page - 1) * $length)->take($length)->with('user', 'orangTuaMahasiswa', 'saudaraMahasiswa', 'sertifikatPrestasi', 'sertifikatOrganisasi')->get();

            }

            return $this->apiResponseGet(200, $count, $mahasiswa);
        } catch (\Exception $e) {
            return $this->apiResponse(500, $e->getMessage(), null);
        }
    }

    public function updateNilaiKelayakan(Request $request)
    {
        $this->validate($request, [
            'beasiswa_id' => 'required',
            'pembobotan' => 'required',
            'kriteria' => 'required',
            'mahasiswa_ids' => 'required',
        ]);
        try {
            $user = Auth::User();
            $waliKelasId = $user->waliKelas->id;
            $pembobotanAlternatif = $request->pembobotan;
            $totalKriteria = count($request->kriteria);
            $totalAlternatif = count($request->mahasiswa_ids);
            $beasiswa = Beasiswa::findOrFail($request->beasiswa_id);
            $pembobotanKriteria = PerbandinganKriteria::where('beasiswa_id', $request->beasiswa_id)->get();
            $eigenKriteria = $this->getEigenValueForAHP($pembobotanKriteria, $totalKriteria);
            $arrEigenAlternatif[] = null;
            $count = 0;
            foreach ($pembobotanAlternatif as $index => $value) {
                $arrEigenAlternatif[$count] = $this->getEigenValueForAHP($value, $totalAlternatif);
                $count++;
            }

            foreach ($request->mahasiswa_ids as $key => $value) {
                $skor_akhir = 0;
                for ($i = 0; $i < $totalKriteria; $i++) {
                    $skor_akhir += $arrEigenAlternatif[$i][$key][0] * $eigenKriteria[$i][0];
                }
                $mahasiswa = Mahasiswa::where('id', $value)->firstOrFail();
                $pendaftaranMahasiswa = $mahasiswa->beasiswa()->wherePivot('beasiswa_id', $request->beasiswa_id)->where('status', 'Mendaftar')->first();
                if (!$pendaftaranMahasiswa) {
                    return $this->apiResponse(200, 'Penilaian sudah dilakukan', null);
                }
                $pendaftaranMahasiswa->pivot->skor_akhir = $skor_akhir;
                $pendaftaranMahasiswa->pivot->status = "Dinilai oleh wali kelas";
                $pendaftaranMahasiswa->pivot->save();
            }

            foreach ($pembobotanAlternatif as $index => $value) {
                foreach ($value as $item) {
                    $perbandingan_alternatif = new PerbandinganAlternatif;
                    $perbandingan_alternatif->beasiswa_id = $request->beasiswa_id;
                    $perbandingan_alternatif->kriteria_id = $request->kriteria[$index];
                    $perbandingan_alternatif->mahasiswa_id_1 = $item['mahasiswa_id_1'];
                    $perbandingan_alternatif->bobot_1 = $item['bobot_1'];
                    $perbandingan_alternatif->mahasiswa_id_2 = $item['mahasiswa_id_2'];
                    $perbandingan_alternatif->bobot_2 = $item['bobot_2'];
                    $perbandingan_alternatif->save();
                }
            }
            return $this->apiResponse(200, 'Berhasil menambahkan penilaian', "Berhasil");
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
