<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Beasiswa;
use App\Mahasiswa;
use App\PendaftarBeasiswa;
use App\KuotaBeasiswa;
use Illuminate\Http\Request;
use DB;


class KetuaProgramStudiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:ketuaProdi');
    }

    // public function getPendaftarProgramStudi(Request $request)
    // {
    //     $this->validate($request, [
    //         'beasiswa_id' => 'required|integer',
    //     ]);
    //     try {
    //         $user = Auth::User();
    //         $prodiId = $user->ketuaProgramStudi->programStudi->id;
    //         $listKuotaProgramStudi = DB::table('beasiswa_program_studi')
    //             ->where('program_studi_id', $prodiId)
    //             ->where('beasiswa_id', $request->beasiswa_id)
    //             ->distinct()->get(['angkatan', 'kuota']);
    //         $listStatusMenerima = ['Lulus seleksi program studi', 'Lulus seleksi jurusan', 'Menerima beasiswa'];
    //         foreach ($listKuotaProgramStudi as $kuotaProgramStudi) {
    //             $beasiswaWithPenerima = Beasiswa::where('id', $request->beasiswa_id)
    //                 ->with(['mahasiswa' => function ($q) use ($listStatusMenerima, $prodiId, $kuotaProgramStudi) {
    //                     $q->where('program_studi_id', $prodiId)
    //                         ->where('angkatan', $kuotaProgramStudi->angkatan)
    //                         ->whereIn('status', $listStatusMenerima);
    //                 }])->firstOrFail();
    //             $jumlahLulusSeleksi = count($beasiswaWithPenerima->mahasiswa);
    //             $kuotaProgramStudi->pendaftarLulusSeleksiProgramStudi = $beasiswaWithPenerima->mahasiswa;
    //             $kuotaProgramStudi->pendaftarBeasiswa = Mahasiswa::where('program_studi_id', $prodiId)
    //                 ->where('angkatan', $kuotaProgramStudi->angkatan)
    //                 ->join('pendaftar_beasiswa', 'id', 'pendaftar_beasiswa.mahasiswa_id')
    //                 ->where('pendaftar_beasiswa.beasiswa_id', $request->beasiswa_id)
    //                 ->where('status', "Dinilai oleh wali kelas")
    //                 ->whereNotIn('mahasiswa_id', function ($q) {
    //                     $q->select('mahasiswa_id')
    //                         ->from('pendaftar_beasiswa')
    //                         ->where('status', 'Lulus seleksi jurusan')
    //                         ->orWhere('status', 'Lulus seleksi program studi')
    //                         ->orWhere('status', 'Menerima beasiswa');
    //                 })->orderBy('pendaftar_beasiswa.skor_akhir', 'desc')
    //                 ->take($kuotaProgramStudi->kuota - $jumlahLulusSeleksi)
    //                 ->get();
    //         }
    //         return $this->apiResponseGet(200, count($listKuotaProgramStudi), $listKuotaProgramStudi);
    //     } catch (\Exception $e) {
    //         return $this->apiResponse(201, $e->getMessage(), null);
    //     }
    // }

//    public function seleksiBeasiswaProgramStudi(Request $request)
//    {
//        $this->validate($request, [
//            'beasiswa_id' => 'required',
//            'mahasiswa_ids' => 'required'
//        ]);
//        try {
//            $user = Auth::User();
//            $prodiId = $user->ketuaProgramStudi->programStudi->id;
//            $listKuotaProgramStudi = DB::table('beasiswa_program_studi')
//                ->where('program_studi_id', $prodiId)
//                ->where('beasiswa_id', $request->beasiswa_id)
//                ->distinct()->get(['angkatan', 'kuota']);
//            foreach ($listKuotaProgramStudi as $kuotaProgramStudi) {
//                $kuotaProgramStudi->pendaftarBeasiswa = Mahasiswa::where('program_studi_id', $prodiId)
//                    ->where('angkatan', $kuotaProgramStudi->angkatan)
//                    ->join('pendaftar_beasiswa', 'id', 'pendaftar_beasiswa.mahasiswa_id')
//                    ->where('pendaftar_beasiswa.beasiswa_id', $request->beasiswa_id)
//                    ->where('status', "Dinilai oleh wali kelas")
//                    ->whereNotIn('mahasiswa_id', function ($q) {
//                        $q->select('mahasiswa_id')
//                            ->from('pendaftar_beasiswa')
//                            ->where('status', 'Lulus seleksi program studi')
//                            ->orWhere('status', 'Lulus seleksi jurusan')
//                            ->orWhere('status', 'Menerima beasiswa');
//                    })->orderBy('pendaftar_beasiswa.skor_akhir', 'desc')
//                    ->take($kuotaProgramStudi->kuota)
//                    ->get(['mahasiswa_id', 'beasiswa_id']);
//            }
//
//            foreach ($listKuotaProgramStudi as $key => $kuotaProgramStudi) {
//                foreach ($kuotaProgramStudi->pendaftarBeasiswa as $pendaftarBeasiswa) {
//                    DB::table('pendaftar_beasiswa')
//                        ->where('beasiswa_id', $pendaftarBeasiswa->beasiswa_id)
//                        ->where('mahasiswa_id', $pendaftarBeasiswa->mahasiswa_id)
//                        ->update(['status' => 'Lulus seleksi program studi']);
//                }
//            }
//            return $this->apiResponse(200, 'success', $listKuotaProgramStudi);
//        } catch (\Exception $e) {
//            return $this->apiResponse(201, $e->getMessage(), null);
//        }
//    }

    public function getPendaftarProgramStudi(Request $request)
    {
        $this->validate($request, [
            'beasiswa_id' => 'required|integer',
        ]);
        try {
            $listStatus = ['Dinilai oleh wali kelas', 'Lulus seleksi program studi', 'Lulus seleksi jurusan', 'Menerima beasiswa'];
            $user = Auth::User();
            $prodiId = $user->ketuaProgramStudi->programStudi->id;
            $pendaftarBeasiswa = PendaftarBeasiswa::select('pendaftar_beasiswa.*', 'mahasiswa.*', 'wali_kelas.nama as wali_kelas_nama')
                ->leftJoin('mahasiswa', 'pendaftar_beasiswa.mahasiswa_id', '=', 'mahasiswa.id')
                ->leftJoin('wali_kelas', 'mahasiswa.wali_kelas_id', '=', 'wali_kelas.id')
                ->where('mahasiswa.program_studi_id', $prodiId)
                ->whereIn('pendaftar_beasiswa.status', $listStatus)
                ->orderBy('angkatan', 'asc')->orderBy('skor_akhir', 'desc')->get();

            return $this->apiResponseGet(200, count($pendaftarBeasiswa), $pendaftarBeasiswa);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function seleksiBeasiswaProgramStudi(Request $request)
    {
        $this->validate($request, [
            'beasiswa_id' => 'required',
            'mahasiswa_ids' => 'required'
        ]);
        $user = Auth::User();
        $prodiId = $user->ketuaProgramStudi->programStudi->id;

        try {
            $kuota = KuotaBeasiswa::select('angkatan','kuota')->where('beasiswa_id', $request->beasiswa_id)->where('program_studi_id', $prodiId)->get();
            $angkatan_list = array();
            foreach ($kuota as $value){
                $angkatan = $value->angkatan;
                $kuota = $value->kuota;
                $angkatan_list[$angkatan] = $kuota;
            }

            $list_mahasiswa = Mahasiswa::whereIn('id', $request->mahasiswa_ids)->get();
            $group_mahasiswa = $list_mahasiswa->groupBy('angkatan');
            $group_mahasiswa->toArray();

            // Cek apakah melebihi kuota atau tidak
            foreach ($group_mahasiswa as $key => $value){
                if (count($value) > $angkatan_list[$key]){
                    return $this->apiResponse(201, "Mahasiswa angkatan ". $key ." yang dipilih melebihi kuota", null);
                }
            }

            foreach ($list_mahasiswa as $value){
                PendaftarBeasiswa::where('beasiswa_id', $request->beasiswa_id)
                    ->where('mahasiswa_id', $value->id)
                    ->update(['status' => 'Lulus seleksi program studi']);
            }

            return $this->apiResponse(200, "Berhasil melakukan seleksi", $list_mahasiswa);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function getKuotaBeasiswa(Request $request)
    {
        $this->validate($request, [
            'beasiswa_id' => 'required',
        ]);

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

        $user = Auth::User();
        $prodiId = $user->ketuaProgramStudi->programStudi->id;
        try {
            $query = KuotaBeasiswa::where('beasiswa_id', $request->beasiswa_id)->where('program_studi_id', $prodiId);

            $count = $query->count();
            $kuota = $query->skip(($page - 1) * $length)->take($length)->get();
            return $this->apiResponseGet(200, $count, $kuota);
        } catch (\Exception $e) {
            return $this->apiResponse(500, $e->getMessage(), null);
        }
    }
}
