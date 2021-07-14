<?php

namespace App\Http\Controllers;

use App\PendaftarBeasiswa;
use Illuminate\Support\Facades\Auth;
use App\Beasiswa;
use App\Mahasiswa;
use App\ProgramStudi;
use App\KuotaBeasiswa;
use Illuminate\Http\Request;
use DB;


class PD3Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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

        $user = Auth::User();
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
            'pembobotan' => 'required',
            'total_kriteria' => 'required',
        ]);

        try {
            $pembobotan = $request->pembobotan;
            $total_kriteria = $request->total_kriteria;
            $cr = $this->getCRforAHP($pembobotan, $total_kriteria);
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
            'pembobotan' => 'required',
            'total_kriteria' => 'required',
        ]);
        try {
            $beasiswa = Beasiswa::findOrFail($request->id);
            $pembobotan = $request->pembobotan;
            $total_kriteria = $request->total_kriteria;
            $cr = $this->getCRforAHP($pembobotan, $total_kriteria);
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

        try {
            if ($request->program_studi_id && $request->angkatan) {
                $kuota = KuotaBeasiswa::where('beasiswa_id', $request->beasiswa_id)
                    ->where('program_studi_id', $request->program_studi_id)
                    ->where('angkatan', $request->angkatan)->get();

                $count = 1;
            } else {
                $query = KuotaBeasiswa::select('beasiswa_program_studi.*', 'program_studi.nama')->where('program_studi.nama', 'like', '%' . $search_text . '%')
                    ->leftJoin('program_studi', 'beasiswa_program_studi.program_studi_id', '=', 'program_studi.id')
                    ->where('beasiswa_program_studi.beasiswa_id', $request->beasiswa_id);

                $count = $query->count();
                $kuota = $query->skip(($page - 1) * $length)->take($length)->get();
            }
            return $this->apiResponseGet(200, $count, $kuota);
        } catch (\Exception $e) {
            return $this->apiResponse(500, $e->getMessage(), null);
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
            return $this->apiResponse(200, 'success', $kuotaBeasiswa);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function storeKuotaBeasiswa(Request $request)
    {
        $this->validate($request, [
            'beasiswa_id' => 'required',
            'program_studi_id' => 'required',
            'angkatan' => 'required',
            'kuota' => 'required',
        ]);
        try {
            if (!$beasiswa = Beasiswa::find($request->beasiswa_id)) {
                return $this->apiResponse(201, "Beasiswa tidak ada", null);
            }
            if (!$programStudi = ProgramStudi::find($request->program_studi_id)) {
                return $this->apiResponse(201, "Program Studi tidak ada", null);
            }
            if ($cek = KuotaBeasiswa::where('beasiswa_id', $request->beasiswa_id)
                ->where('program_studi_id', $request->program_studi_id)
                ->where('angkatan', $request->angkatan)->first()) {
                return $this->apiResponse(201, "Kuota sudah ada", null);
            }
            $kuota_beasiswa = KuotaBeasiswa::create($request->all());

            return $this->apiResponse(200, 'Berhasil menambahkan kuota', $kuota_beasiswa);
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
            if (!$beasiswa = Beasiswa::find($request->beasiswa_id)) {
                return $this->apiResponse(201, "Beasiswa tidak ada", null);
            }
            if (!$programStudi = ProgramStudi::find($request->program_studi_id)) {
                return $this->apiResponse(201, "Program Studi tidak ada", null);
            }
            $kuota_beasiswa = KuotaBeasiswa::where('beasiswa_id', $request->beasiswa_id)
                ->where('program_studi_id', $request->program_studi_id)
                ->where('angkatan', $request->angkatan);

            $kuota_beasiswa->update($request->all());

            return $this->apiResponse(200, 'Berhasil mengubah kuota', $kuota_beasiswa);
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
            $kuota_beasiswa = KuotaBeasiswa::where('beasiswa_id', $request->beasiswa_id)
                ->where('program_studi_id', $request->program_studi_id)
                ->where('angkatan', $request->angkatan);

            $kuota_beasiswa->delete();
            return $this->apiResponse(200, 'success', $kuota_beasiswa);
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
            if (!$request->search_text) {
                $search_text = "";
            } else {
                $search_text = $request->search_text;
            }
            $listStatus = ['Lulus seleksi jurusan', 'Menerima beasiswa'];
            $pendaftarBeasiswa = PendaftarBeasiswa::select('pendaftar_beasiswa.*', 'mahasiswa.*', 'wali_kelas.nama as wali_kelas_nama', 'program_studi.nama as program_studi_nama')
                ->leftJoin('mahasiswa', 'pendaftar_beasiswa.mahasiswa_id', '=', 'mahasiswa.id')
                ->leftJoin('wali_kelas', 'mahasiswa.wali_kelas_id', '=', 'wali_kelas.id')
                ->leftJoin('program_studi', 'mahasiswa.program_studi_id', '=', 'program_studi.id')
                ->where('pendaftar_beasiswa.beasiswa_id', $request->beasiswa_id)
                ->where('mahasiswa.nama', 'like', '%' . $search_text . '%')
                ->whereIn('pendaftar_beasiswa.status', $listStatus)
                ->orderBy('program_studi_id', 'asc')->orderBy('angkatan', 'asc')->orderBy('skor_akhir', 'desc')->get();

            return $this->apiResponseGet(200, count($pendaftarBeasiswa), $pendaftarBeasiswa);

        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function seleksiBeasiswa(Request $request)
    {
        $this->validate($request, [
            'beasiswa_id' => 'required|integer',
            'mahasiswa_ids' => 'required'
        ]);
        try {
            $beasiswa = Beasiswa::findOrFail($request->beasiswa_id);
            if ($beasiswa->status_pendaftaran == 'Ditutup'){
                return $this->apiResponse(200, 'Beasiswa sudah dilakukan pemilihan penerima beasiswa', null);
            }
            $list_mahasiswa = Mahasiswa::whereIn('id', $request->mahasiswa_ids)->get();

            foreach ($list_mahasiswa as $value) {
                PendaftarBeasiswa::where('beasiswa_id', $request->beasiswa_id)
                    ->where('mahasiswa_id', $value->id)
                    ->update(['status' => 'Menerima beasiswa']);
            }

            $beasiswa->status_pendaftaran = "Ditutup";
            $beasiswa->save();
            return $this->apiResponse(200, 'Berhasil melakukan seleksi penerima beasiswa', $list_mahasiswa);
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
            return $this->apiResponse(200, 'success', $listPenerimaBeasiswa);
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
