<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Beasiswa;
use App\Mahasiswa;
use App\ProgramStudi;
use Illuminate\Http\Request;
use DB;


class PD3Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:pd3');
    }

    public function storeBeasiswa(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|string',
            'deskripsi' => 'required|string',
            'awal_pendaftaran' => 'required|date|before:akhir_pendaftaran',
            'akhir_pendaftaran' => 'required|date|after:awal_pendaftaran',
            'awal_penerimaan' => 'required|date|after:awal_pendaftaran|after:akhir_pendaftaran|before:akhir_penerimaan',
            'akhir_penerimaan' => 'required|date|after:awal_pendaftaran|after:akhir_pendaftaran|after:awal_penerimaan',
            'biaya_pendidikan_per_semester' => 'required|integer',
            'penghasilan_orang_tua_maksimal' => 'required|integer',
            'ipk_minimal' => 'required',
            'bobot_ipk' => 'required|integer|gt:0|lte:100',
            'bobot_prestasi' => 'required|integer|gt:0|lte:100',
            'bobot_perilaku' => 'required|integer|gt:0|lte:100',
            'bobot_organisasi' => 'required|integer|gt:0|lte:100',
            'bobot_kemampuan_ekonomi' => 'required|integer|gt:0|lte:100',

        ]);

        try {
            if ($request->bobot_ipk + $request->bobot_prestasi + $request->bobot_perilaku + $request->bobot_organisasi + $request->bobot_kemampuan_ekonomi != 100) {
                return $this->apiResponse(201, 'Bobot Penilaian Tidak Sesuai', null);
            }
            $beasiswa = Beasiswa::create($request->all());
            return $this->apiResponse(200, 'success', ['beasiswa' => $beasiswa]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function updateBeasiswa(Request $request)
    {
        $this->validate($request, [
            'beasiswa_id' => 'required|integer',
            'nama' => 'string',
            'deskripsi' => 'string',
            'awal_pendaftaran' => 'date|before:akhir_pendaftaran',
            'akhir_pendaftaran' => 'date|after:awal_pendaftaran',
            'awal_penerimaan' => 'date|after:awal_pendaftaran|after:akhir_pendaftaran|before:akhir_penerimaan',
            'akhir_penerimaan' => 'date|after:awal_pendaftaran|after:akhir_pendaftaran|after:awal_penerimaan',
            'biaya_pendidikan_per_semester' => 'integer',
            'penghasilan_orang_tua_maksimal' => 'integer',
            // 'ipk_minimal' => 'required'
            'bobot_ipk' => 'required|integer|gt:0|lte:100',
            'bobot_prestasi' => 'required|integer|gt:0|lte:100',
            'bobot_perilaku' => 'required|integer|gt:0|lte:100',
            'bobot_organisasi' => 'required|integer|gt:0|lte:100',
            'bobot_kemampuan_ekonomi' => 'required|integer|gt:0|lte:100',
        ]);
        try {
            if ($request->bobot_ipk + $request->bobot_prestasi + $request->bobot_perilaku + $request->bobot_organisasi + $request->bobot_kemampuan_ekonomi != 100) {
                return $this->apiResponse(201, 'Bobot Penilaian Tidak Sesuai', null);
            }
            $beasiswa = Beasiswa::findOrFail($request->beasiswa_id);
            $beasiswa->update($request->all());
            return $this->apiResponse(200, 'success', ['beasiswa' => $beasiswa]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function destroyBeasiswa(Request $request)
    {
        $this->validate($request, [
            'beasiswa_id' => 'required|integer',
        ]);
        try {
            $beasiswa = Beasiswa::findOrFail($request->beasiswa_id);
            Beasiswa::destroy($request->beasiswa_id);
            return $this->apiResponse(200, 'success', ['beasiswa' => $beasiswa]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function getKuotaBeasiswa(Request $request)
    {
        $this->validate($request, [
            'beasiswa_id' => 'required|integer',
        ]);
        try {
            $kuotaBeasiswa = Beasiswa::where('id', $request->beasiswa_id)
                ->with('programStudi')
                ->firstOrFail();
            return $this->apiResponse(200, 'success', ['kuota_beasiswa' => $kuotaBeasiswa]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }

    }

    public function getKuotaBeasiswaProgamStudiAngkatan(Request $request)
    {
        $this->validate($request, [
            'beasiswa_id' => 'required|integer',
            'program_studi_id' => 'required|integer',
            'angkatan' => 'required|integer',
        ]);
        try {
            $kuotaBeasiswa = Beasiswa::whereHas('programStudi', function ($q) use ($request) {
                $q->where('beasiswa_id', $request->beasiswa_id);
            })->with(['programStudi' => function ($q) use ($request) {
                $q->where('program_studi_id', $request->program_studi_id)->where('angkatan', $request->angkatan);
            }])->firstOrFail();

            $programStudi = $kuotaBeasiswa->programStudi[0];
            unset($kuotaBeasiswa->programStudi);
            $kuotaBeasiswa->programStudi = $programStudi;
            return $this->apiResponse(200, 'success', ['kuota_beasiswa' => $kuotaBeasiswa]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function storeKuotaBeasiswa(Request $request)
    {
        $this->validate($request, [
            'beasiswa_id' => 'required|integer',
            'program_studi_id' => 'required|integer',
            'angkatan' => 'required|integer',
            'kuota' => 'required|integer',
        ]);
        try {
            $beasiswa = Beasiswa::findOrFail($request->beasiswa_id);
            $programStudi = ProgramStudi::findOrFail($request->program_studi_id);
            $beasiswa->programStudi()->attach($programStudi->id, ['angkatan' => $request->angkatan, 'kuota' => $request->kuota]);
            return $this->apiResponse(200, 'success', ['kuota_beasiswa' => $beasiswa->programStudi]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function updateKuotaBeasiswa(Request $request)
    {
        $this->validate($request, [
            'beasiswa_id' => 'required|integer',
            'program_studi_id' => 'required|integer',
            'angkatan' => 'required|integer',
            'kuota' => 'required|integer',
        ]);
        try {
            $beasiswa = Beasiswa::findOrFail($request->beasiswa_id);
            $programStudi = ProgramStudi::findOrFail($request->program_studi_id);
            $updatedBeasiswa = $beasiswa->programStudi()->wherePivot('program_studi_id', $programStudi->id)->wherePivot('angkatan', $request->angkatan);
            $updatedBeasiswa->updateExistingPivot([$programStudi->id, $request->angkatan], ['kuota' => $request->kuota]);
            return $this->apiResponse(200, 'success', ['kuota_beasiswa' => $updatedBeasiswa]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function destroyKuotaBeasiswa(Request $request)
    {
        $this->validate($request, [
            'beasiswa_id' => 'required|integer',
            'program_studi_id' => 'required|integer',
            'angkatan' => 'required|integer',
        ]);
        try {
            $beasiswa = Beasiswa::findOrFail($request->beasiswa_id);
            $programStudi = ProgramStudi::findOrFail($request->program_studi_id);
            $beasiswa->programStudi()->wherePivot('angkatan', $request->angkatan)->wherePivot('program_studi_id', $programStudi->id)->detach();
            return $this->apiResponse(200, 'success', null);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function getPendaftar(Request $request)
    {
        $this->validate($request, [
            'beasiswa_id' => 'required|integer',
        ]);
        try {
            $listKuotaBeasiswa = DB::table('beasiswa_program_studi')
                ->where('beasiswa_id', $request->beasiswa_id)
                ->distinct()
                ->get(['angkatan', 'program_studi_id', 'kuota']);

            foreach ($listKuotaBeasiswa as $kuotaBeasiswa) {
                $kuotaBeasiswa->pendaftarBeasiswa = Mahasiswa::where('program_studi_id', $kuotaBeasiswa->program_studi_id)
                    ->where('angkatan', $kuotaBeasiswa->angkatan)
                    ->join('pendaftar_beasiswa', 'id', 'pendaftar_beasiswa.mahasiswa_id')
                    ->where('pendaftar_beasiswa.beasiswa_id', $request->beasiswa_id)
                    ->where('status', "Lulus seleksi jurusan")
                    ->whereNotIn('mahasiswa_id', function ($q) {
                        $q->select('mahasiswa_id')
                            ->from('pendaftar_beasiswa')
                            ->where('status', 'Menerima beasiswa');
                    })->orderBy('pendaftar_beasiswa.skor_akhir', 'desc')
                    ->take($kuotaBeasiswa->kuota)
                    ->get();
            }
            return $this->apiResponse(200, 'success', ['pendaftar_beasiswa' => $listKuotaBeasiswa]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function seleksiBeasiswa(Request $request)
    {
        $this->validate($request, [
            'beasiswa_id' => 'required|integer',
        ]);
        try {
            $listKuotaBeasiswa = DB::table('beasiswa_program_studi')
                ->where('beasiswa_id', $request->beasiswa_id)
                ->distinct()
                ->get(['angkatan', 'program_studi_id', 'kuota']);
            foreach ($listKuotaBeasiswa as $kuotaBeasiswa) {
                $kuotaBeasiswa->pendaftarBeasiswa = Mahasiswa::where('program_studi_id', $kuotaBeasiswa->program_studi_id)
                    ->where('angkatan', $kuotaBeasiswa->angkatan)
                    ->join('pendaftar_beasiswa', 'id', 'pendaftar_beasiswa.mahasiswa_id')
                    ->where('pendaftar_beasiswa.beasiswa_id', $request->beasiswa_id)
                    ->where('status', "Lulus seleksi jurusan")
                    ->whereNotIn('mahasiswa_id', function ($q) {
                        $q->select('mahasiswa_id')
                            ->from('pendaftar_beasiswa')
                            ->where('status', 'Menerima beasiswa');
                    })->orderBy('pendaftar_beasiswa.skor_akhir', 'desc')
                    ->take($kuotaBeasiswa->kuota)
                    ->get();
            }
            foreach ($listKuotaBeasiswa as $key => $kuotaBeasiswa) {
                foreach ($kuotaBeasiswa->pendaftarBeasiswa as $pendaftarBeasiswa) {
                    DB::table('pendaftar_beasiswa')
                        ->where('beasiswa_id', $pendaftarBeasiswa->beasiswa_id)
                        ->where('mahasiswa_id', $pendaftarBeasiswa->mahasiswa_id)
                        ->update(['status' => 'Menerima beasiswa']);
                }
            }
            $beasiswa = Beasiswa::findOrFail($request->id);
            $beasiswa->status_pendaftaran = "Ditutup";
            $beasiswa->save();
            return $this->apiResponse(200, 'success', ['pendaftar_beasiswa' => $listKuotaBeasiswa]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function getPenerimaBeasiswa(Request $request)
    {
        $this->validate($request, [
            'beasiswa_id' => 'required|integer',
        ]);
        try {
            $listPenerimaBeasiswa = Mahasiswa::where('program_studi_id', $kuotaAngkatan->program_studi_id)
                ->join('pendaftar_beasiswa', 'id', 'pendaftar_beasiswa.mahasiswa_id')
                ->where('pendaftar_beasiswa.beasiswa_id', $request->beasiswa_id)
                ->where('status', "Menerima beasiswa")
                ->orderBy('pendaftar_beasiswa.skor_akhir', 'desc')
                ->get();
            return $this->apiResponse(200, 'success', ['penerima_beasiswa' => $listPenerimaBeasiswa]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    // public function penyelesaianBeasiswa(Request $request)
    // {
    //     $this->validate($request, [
    //         'beasiswa_id' => 'required|integer',
    //     ]);
    //     try{
    //         $listKuotaAngkatan = DB::table('beasiswa_program_studi')->where('beasiswa_id', $request->beasiswa_id)->distinct()->get(['angkatan','program_studi_id', 'kuota']);
    //         foreach ($listKuotaAngkatan as $kuotaAngkatan) {
    //             $kuotaAngkatan->pendaftarBeasiswa = Mahasiswa::where('program_studi_id', $kuotaAngkatan->program_studi_id)
    //             ->where('angkatan', $kuotaAngkatan->angkatan)
    //             ->join('pendaftar_beasiswa', 'id', 'pendaftar_beasiswa.mahasiswa_id')
    //             ->where('pendaftar_beasiswa.beasiswa_id', $request->beasiswa_id)
    //             ->where('status', "Menerima beasiswa")
    //             ->whereNotIn('mahasiswa_id', function($q) use ($request){
    //                 $q->select('mahasiswa_id')
    //                 ->from('pendaftar_beasiswa')
    //                 ->where('status', 'Menerima beasiswa')
    //                 ->where('beasiswa_id', '!=', $request->beasiswa_id);
    //             })->orderBy('pendaftar_beasiswa.skor_akhir', 'desc')
    //             ->take($kuotaAngkatan->kuota)
    //             ->get();
    //         }
    //         foreach ($listKuotaAngkatan as $key => $kuotaAngkatan) {
    //             foreach ($kuotaAngkatan->pendaftarBeasiswa as $pendaftarBeasiswa) {
    //                 DB::table('pendaftar_beasiswa')
    //                 ->where('beasiswa_id', $pendaftarBeasiswa->beasiswa_id)
    //                 ->where('mahasiswa_id', $pendaftarBeasiswa->mahasiswa_id)
    //                 ->update(['status' => 'Selesai menerima beasiswa']);
    //             }
    //         }
    //         return $this->apiResponse(200, 'success', ['pendaftar_program_studi' => $listKuotaAngkatan]);
    //     }catch (\Exception $e) {
    //         return $this->apiResponse(201, $e->getMessage(), null);
    //     }
    // }


}
