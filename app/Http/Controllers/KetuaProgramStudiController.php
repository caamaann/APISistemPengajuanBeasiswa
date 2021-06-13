<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Beasiswa;
use App\Mahasiswa;
use Illuminate\Http\Request;
use DB;


class KetuaProgramStudiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:ketuaProdi');
    }

    public function getPendaftarProgramStudi(Request $request)
    {
        $this->validate($request, [
            'beasiswa_id' => 'required|integer',
        ]);
        try {
            $user = Auth::User();
            $prodiId = $user->ketuaProgramStudi->programStudi->id;
            $listKuotaProgramStudi = DB::table('beasiswa_program_studi')
                ->where('program_studi_id', $prodiId)
                ->where('beasiswa_id', $request->beasiswa_id)
                ->distinct()->get(['angkatan', 'kuota']);
            $listStatusMenerima = ['Lulus seleksi program studi', 'Lulus seleksi jurusan', 'Menerima beasiswa'];
            foreach ($listKuotaProgramStudi as $kuotaProgramStudi) {
                $beasiswaWithPenerima = Beasiswa::where('id', $request->beasiswa_id)
                    ->with(['mahasiswa' => function ($q) use ($listStatusMenerima, $prodiId, $kuotaProgramStudi) {
                        $q->where('program_studi_id', $prodiId)
                            ->where('angkatan', $kuotaProgramStudi->angkatan)
                            ->whereIn('status', $listStatusMenerima);
                    }])->firstOrFail();
                $jumlahLulusSeleksi = count($beasiswaWithPenerima->mahasiswa);
                $kuotaProgramStudi->pendaftarLulusSeleksiProgramStudi = $beasiswaWithPenerima->mahasiswa;
                $kuotaProgramStudi->pendaftarBeasiswa = Mahasiswa::where('program_studi_id', $prodiId)
                    ->where('angkatan', $kuotaProgramStudi->angkatan)
                    ->join('pendaftar_beasiswa', 'id', 'pendaftar_beasiswa.mahasiswa_id')
                    ->where('pendaftar_beasiswa.beasiswa_id', $request->beasiswa_id)
                    ->where('status', "Dinilai oleh wali kelas")
                    ->whereNotIn('mahasiswa_id', function ($q) {
                        $q->select('mahasiswa_id')
                            ->from('pendaftar_beasiswa')
                            ->where('status', 'Lulus seleksi jurusan')
                            ->orWhere('status', 'Lulus seleksi program studi')
                            ->orWhere('status', 'Menerima beasiswa');
                    })->orderBy('pendaftar_beasiswa.skor_akhir', 'desc')
                    ->take($kuotaProgramStudi->kuota - $jumlahLulusSeleksi)
                    ->get();
            }
            return $this->apiResponse(200, 'success', ['pendaftar_program_studi' => $listKuotaProgramStudi]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function seleksiBeasiswaProgramStudi(Request $request)
    {
        $this->validate($request, [
            'beasiswa_id' => 'required|integer',
        ]);
        try {
            $user = Auth::User();
            $prodiId = $user->ketuaProgramStudi->programStudi->id;
            $listKuotaProgramStudi = DB::table('beasiswa_program_studi')
                ->where('program_studi_id', $prodiId)
                ->where('beasiswa_id', $request->beasiswa_id)
                ->distinct()->get(['angkatan', 'kuota']);
            foreach ($listKuotaProgramStudi as $kuotaProgramStudi) {
                $kuotaProgramStudi->pendaftarBeasiswa = Mahasiswa::where('program_studi_id', $prodiId)
                    ->where('angkatan', $kuotaProgramStudi->angkatan)
                    ->join('pendaftar_beasiswa', 'id', 'pendaftar_beasiswa.mahasiswa_id')
                    ->where('pendaftar_beasiswa.beasiswa_id', $request->beasiswa_id)
                    ->where('status', "Dinilai oleh wali kelas")
                    ->whereNotIn('mahasiswa_id', function ($q) {
                        $q->select('mahasiswa_id')
                            ->from('pendaftar_beasiswa')
                            ->where('status', 'Lulus seleksi program studi')
                            ->orWhere('status', 'Lulus seleksi jurusan')
                            ->orWhere('status', 'Menerima beasiswa');
                    })->orderBy('pendaftar_beasiswa.skor_akhir', 'desc')
                    ->take($kuotaProgramStudi->kuota)
                    ->get(['mahasiswa_id', 'beasiswa_id']);
            }

            foreach ($listKuotaProgramStudi as $key => $kuotaProgramStudi) {
                foreach ($kuotaProgramStudi->pendaftarBeasiswa as $pendaftarBeasiswa) {
                    DB::table('pendaftar_beasiswa')
                        ->where('beasiswa_id', $pendaftarBeasiswa->beasiswa_id)
                        ->where('mahasiswa_id', $pendaftarBeasiswa->mahasiswa_id)
                        ->update(['status' => 'Lulus seleksi program studi']);
                }
            }
            return $this->apiResponse(200, 'success', ['pendaftar_program_studi' => $listKuotaProgramStudi]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }
}
