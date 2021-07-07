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

    public function getBeasiswa(Request $request)
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
            if ($request->id) {
                $beasiswa = array(Beasiswa::findOrFail($request->id));
                $beasiswa[0]->programStudi;
                $pembobotan = PerbandinganKriteria::where('beasiswa_id', $request->id)->get();
                $beasiswa[0]->pembobotan = $pembobotan;

                $count = 1;
            } else {
                $query = Beasiswa::where('nama', 'like', '%' . $search_text . '%');
                if ($request->is_active === 1) {
                    $query->where('awal_pendaftaran', '<=', Carbon::now())->where('akhir_pendaftaran', '>=', Carbon::now());
                } else if ($request->is_active === 0) {
                    $query->where('awal_pendaftaran', '>', Carbon::now())->orWhere('akhir_pendaftaran', '<', Carbon::now());
                }

                $count = $query->count();
                $beasiswa = $query->skip(($page - 1) * $length)->take($length)->get();
            }
            return $this->apiResponseGet(200, $count, $beasiswa);
        } catch (\Exception $e) {
            return $this->apiResponse(500, $e->getMessage(), null);
        }
    }

    public function storeBeasiswa(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|string',
            'deskripsi' => 'string',
            'awal_pendaftaran' => 'required|date|before:akhir_pendaftaran',
            'akhir_pendaftaran' => 'required|date|after:awal_pendaftaran',
            'awal_penerimaan' => 'required|date|after:awal_pendaftaran|after:akhir_pendaftaran|before:akhir_penerimaan',
            'akhir_penerimaan' => 'required|date|after:awal_pendaftaran|after:akhir_pendaftaran|after:awal_penerimaan',
            'penghasilan_orang_tua_maksimal' => 'required',
            'ipk_minimal' => 'required',
        ]);

        try {
            $pembobotan = $request->pembobotan;
            $cr = $this->getCRforAHP($pembobotan);
            if ($cr >= 0.1) {
                return $this->apiResponse(200, 'Perbandingan tidak konsisten', null);
            }

            $beasiswa = Beasiswa::create($request->all());
            foreach ($pembobotan as $item) {
                $perbandingan_kriteria = new PerbandinganKriteria;
                $perbandingan_kriteria->beasiswa_id = $beasiswa->id;
                $perbandingan_kriteria->kriteria_1 = $item['kriteria_1'];
                $perbandingan_kriteria->bobot_1 = $item['bobot_1'];
                $perbandingan_kriteria->kriteria_2 = $item['kriteria_2'];
                $perbandingan_kriteria->bobot_2 = $item['bobot_2'];
                $perbandingan_kriteria->save();
            }

            return $this->apiResponse(200, 'Beasiswa berhasil ditambahkan', $beasiswa);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function updateBeasiswa(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'nama' => 'string',
            'deskripsi' => 'string',
            'awal_pendaftaran' => 'date|before:akhir_pendaftaran',
            'akhir_pendaftaran' => 'date|after:awal_pendaftaran',
            'awal_penerimaan' => 'date|after:awal_pendaftaran|after:akhir_pendaftaran|before:akhir_penerimaan',
            'akhir_penerimaan' => 'date|after:awal_pendaftaran|after:akhir_pendaftaran|after:awal_penerimaan',
            'penghasilan_orang_tua_maksimal' => 'required',
            'ipk_minimal' => 'required',
        ]);
        try {
            $beasiswa = Beasiswa::findOrFail($request->id);
            $pembobotan = $request->pembobotan;
            $cr = $this->getCRforAHP($pembobotan);
            if ($cr >= 0.1) {
                return $this->apiResponse(200, 'Perbandingan tidak konsisten', null);
            }

            $beasiswa->update($request->all());
            if ($bobot = PerbandinganKriteria::where('beasiswa_id', $request->id)) {
                $bobot->delete();
            }

            foreach ($pembobotan as $item) {
                $perbandingan_kriteria = new PerbandinganKriteria;
                $perbandingan_kriteria->beasiswa_id = $beasiswa->id;
                $perbandingan_kriteria->kriteria_1 = $item['kriteria_1'];
                $perbandingan_kriteria->bobot_1 = $item['bobot_1'];
                $perbandingan_kriteria->kriteria_2 = $item['kriteria_2'];
                $perbandingan_kriteria->bobot_2 = $item['bobot_2'];
                $perbandingan_kriteria->save();
            }

            return $this->apiResponse(200, 'Beasiswa berhasil diubah', $beasiswa);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function destroyBeasiswa(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);
        try {
            $beasiswa = Beasiswa::findOrFail($request->id);
            if ($bobot = PerbandinganKriteria::where('beasiswa_id', $request->id)) {
                $bobot->delete();
            }
            Beasiswa::destroy($request->id);
            return $this->apiResponse(200, 'Beasiswa berhasil dihapus', $beasiswa);
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
