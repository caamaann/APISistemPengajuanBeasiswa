<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Beasiswa;
use App\Mahasiswa;
use Illuminate\Http\Request;
use DB;


class KetuaJurusanController extends Controller
{
    /**
     * Instantiate a new UserController instance that guarded by auth and role middleware.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:ketuaJurusan');
    }

    public function getPendaftarJurusan(Request $request)
    {
        $this->validate($request, [
            'beasiswa_id' => 'required|integer',
        ]);
        try {
            $user = Auth::User();
            $listProgramStudi = $user->ketuaJurusan->jurusan->programStudi;
            $listProgramStudiId = array();
            foreach ($listProgramStudi as $key => $programStudi) {
                array_push($listProgramStudiId, $programStudi->id);
            }

            $listProdiAngkatan = DB::table('beasiswa_program_studi')
                ->join('program_studi', 'program_studi_id', 'program_studi.id')
                ->whereIn('program_studi_id', $listProgramStudiId)
                ->where('beasiswa_id', $request->beasiswa_id)
                ->get(['angkatan', 'program_studi_id', 'program_studi.nama', 'kuota']);

            foreach ($listProdiAngkatan as $key => $prodiAngkatan) {
                $prodiAngkatan->pendaftarBeasiswa = Mahasiswa::where('program_studi_id', $prodiAngkatan->program_studi_id)
                    ->where('angkatan', $prodiAngkatan->angkatan)
                    ->join('pendaftar_beasiswa', 'id', 'pendaftar_beasiswa.mahasiswa_id')
                    ->where('pendaftar_beasiswa.beasiswa_id', $request->beasiswa_id)
                    ->where('status', 'Lulus seleksi program studi')
                    ->whereNotIn('mahasiswa_id', function ($q) {
                        $q->select('mahasiswa_id')
                            ->from('pendaftar_beasiswa')
                            ->where('status', 'Lulus seleksi jurusan')
                            ->orWhere('status', 'Menerima beasiswa');
                    })->orderBy('pendaftar_beasiswa.skor_akhir', 'desc')
                    ->get();
            }

            // foreach ($listKuotaJurusan as $key => $kuotaJurusan) {
            //     $kuotaJurusan->pendaftarBeasiswa = Mahasiswa::whereIn('program_studi_id', $listProgramStudiId)
            //         ->where('angkatan', $kuotaJurusan->angkatan)
            //         ->join('pendaftar_beasiswa', 'id', 'pendaftar_beasiswa.mahasiswa_id')
            //         ->where('pendaftar_beasiswa.beasiswa_id', $request->beasiswa_id)
            //         ->where('status', 'Lulus seleksi program studi')
            //         ->whereNotIn('mahasiswa_id', function($q){
            //             $q->select('mahasiswa_id')
            //             ->from('pendaftar_beasiswa')
            //             ->where('status', 'Lulus seleksi jurusan')
            //             ->orWhere('status', 'Menerima beasiswa');
            //         })->orderBy('pendaftar_beasiswa.skor_akhir', 'desc')
            //         ->get();
            // }
            return $this->apiResponse(200, 'success', ['pendaftar_jurusan' => $listProdiAngkatan]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function seleksiBeasiswaJurusan(Request $request)
    {
        $this->validate($request, [
            'beasiswa_id' => 'required|integer',
        ]);
        try {
            $user = Auth::User();
            $listProgramStudi = $user->ketuaJurusan->jurusan->programStudi;
            $listProgramStudiId = array();
            foreach ($listProgramStudi as $key => $programStudi) {
                array_push($listProgramStudiId, $programStudi->id);
            }

            $listKuotaJurusan = DB::table('beasiswa_program_studi')
                ->whereIn('program_studi_id', $listProgramStudiId)
                ->where('beasiswa_id', $request->beasiswa_id)
                ->distinct()->get(['angkatan']);

            foreach ($listKuotaJurusan as $key => $kuotaJurusan) {
                $kuotaJurusan->pendaftarJurusan = Mahasiswa::whereIn('program_studi_id', $listProgramStudiId)
                    ->where('angkatan', $kuotaJurusan->angkatan)
                    ->join('pendaftar_beasiswa', 'id', 'pendaftar_beasiswa.mahasiswa_id')
                    ->where('pendaftar_beasiswa.beasiswa_id', $request->beasiswa_id)
                    ->where('status', 'Lulus seleksi program studi')
                    ->whereNotIn('mahasiswa_id', function ($q) {
                        $q->select('mahasiswa_id')
                            ->from('pendaftar_beasiswa')
                            ->where('status', 'Lulus seleksi jurusan')
                            ->orWhere('status', 'Menerima beasiswa');
                    })->orderBy('pendaftar_beasiswa.skor_akhir', 'desc')
                    ->get(['mahasiswa_id', 'beasiswa_id']);
            }

            foreach ($listKuotaJurusan as $kuotaJurusan) {
                foreach ($kuotaJurusan->pendaftarJurusan as $pendaftarJurusan) {
                    DB::table('pendaftar_beasiswa')
                        ->where('beasiswa_id', $pendaftarJurusan->beasiswa_id)
                        ->where('mahasiswa_id', $pendaftarJurusan->mahasiswa_id)
                        ->update(['status' => 'Lulus seleksi jurusan']);
                }
            }
            return $this->apiResponse(200, 'success', ['pendaftar_jurusan' => $listKuotaJurusan]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }
}
