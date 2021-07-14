<?php

namespace App\Http\Controllers;

use App\Mahasiswa;
use App\PerbandinganKriteria;
use App\PendaftarBeasiswa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Beasiswa;
use App\ProgramStudi;
use App\KuotaBeasiswa;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;


class BeasiswaController extends Controller
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
                if ($mahasiswa = $user->mahasiswa) {
                    $hasBeasiswa = PendaftarBeasiswa::where('mahasiswa_id', $mahasiswa->id)->where('beasiswa_id', $request->id)->get();
                    if (count($hasBeasiswa) > 0) {
                        $beasiswa[0]->status = 1;
                    } else {
                        $beasiswa[0]->status = 0;
                    }
                }

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

                if ($user) {

                    if ($mahasiswa = $user->mahasiswa) {
                        foreach ($beasiswa as $value) {
                            $hasBeasiswa = PendaftarBeasiswa::where('mahasiswa_id', $mahasiswa->id)->where('beasiswa_id', $value->id)->get();
                            if (count($hasBeasiswa) > 0) {
                                $value->status = 1;
                            } else {
                                $value->status = 0;
                            }
                        }
                    }
                    if ($wali_kelas = $user->waliKelas) {
                        foreach ($beasiswa as $value) {
                            $query = PendaftarBeasiswa::select('pendaftar_beasiswa.*', 'mahasiswa.wali_kelas_id')
                                ->leftJoin('mahasiswa', 'pendaftar_beasiswa.mahasiswa_id', '=', 'mahasiswa.id')
                                ->where('pendaftar_beasiswa.beasiswa_id', $value->id)
                                ->where('mahasiswa.wali_kelas_id', $wali_kelas->id);
                            $value->total_pendaftar = $query->count();
                            $sudahDinilai = $query->where('pendaftar_beasiswa.status', '!=', 'Mendaftar')->get();
                            if (count($sudahDinilai) > 0) {
                                $value->status = 1;
                            } else {
                                $value->status = 0;
                            }
                        }
                    }
                    if ($ketua_prodi = $user->ketuaProgramStudi) {
                        foreach ($beasiswa as $value) {
                            $query = PendaftarBeasiswa::select('pendaftar_beasiswa.*', 'mahasiswa.wali_kelas_id')
                                ->leftJoin('mahasiswa', 'pendaftar_beasiswa.mahasiswa_id', '=', 'mahasiswa.id')
                                ->where('pendaftar_beasiswa.beasiswa_id', $value->id)
                                ->where('mahasiswa.program_studi_id', $ketua_prodi->programStudi->id);
                            $value->total_pendaftar = $query->count();
                            $sudahDinilai = $query->where('pendaftar_beasiswa.status', '!=', 'Mendaftar')
                                ->where('pendaftar_beasiswa.status', '!=', 'Dinilai oleh wali kelas')->get();
                            if (count($sudahDinilai) > 0) {
                                $value->status = 1;
                            } else {
                                $value->status = 0;
                            }
                        }
                    }
                    if ($ketua_jurusan = $user->ketuaJurusan) {
                        foreach ($beasiswa as $value) {
                            $query = PendaftarBeasiswa::select('pendaftar_beasiswa.*', 'mahasiswa.wali_kelas_id')
                                ->leftJoin('mahasiswa', 'pendaftar_beasiswa.mahasiswa_id', '=', 'mahasiswa.id')
                                ->leftJoin('program_studi', 'mahasiswa.program_studi_id', '=', 'program_studi.id')
                                ->where('pendaftar_beasiswa.beasiswa_id', $value->id)
                                ->where('program_studi.jurusan_id', $ketua_jurusan->jurusan->id);
                            $value->total_pendaftar = $query->count();
                            $sudahDinilai = $query->where('pendaftar_beasiswa.status', '!=', 'Mendaftar')
                                ->where('pendaftar_beasiswa.status', '!=', 'Dinilai oleh wali kelas')
                                ->where('pendaftar_beasiswa.status', '!=', 'Lulus seleksi program studi')
                                ->get();
                            if (count($sudahDinilai) > 0) {
                                $value->status = 1;
                            } else {
                                $value->status = 0;
                            }
                        }
                    }
					if ($pd3 = $user->pembantuDirektur3) {
                        foreach ($beasiswa as $value) {
                            $query = KuotaBeasiswa::where('beasiswa_id', $value->id);
                            $value->total_kuota = $query->sum('kuota');
                        }
                    }
                }

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
}
