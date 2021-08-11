<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\User;
use App\Mahasiswa;
use App\OrangTuaMahasiswa;
use App\SaudaraMahasiswa;
use App\Beasiswa;
use App\SertifikatOrganisasi;
use App\SertifikatPrestasi;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;


class MahasiswaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function update(Request $request)
    {
        try {
            $user = Auth::User();
            $mahasiswa = $user->mahasiswa;
            $this->validate($request, [
                'nama' => 'string',
                'tempat_lahir' => 'string',
                'tanggal_lahir' => 'date|before:today',
                'gender' => 'string',
                'nama_bank' => 'string',
                'nomor_rekening' => 'string',
                'alamat' => 'string',
                'kota' => 'string',
                'kode_pos' => 'string',
                'nomor_hp' => 'string',
            ]);
            $mahasiswa->fill($request->only(['nama', 'tempat_lahir', 'tanggal_lahir', 'gender', 'nama_bank', 'nomor_rekening', 'alamat', 'kota', 'kode_pos', 'nomor_hp']));
            $mahasiswa->save();
            return $this->apiResponse(200, 'Mahasiswa berhasil diubah', $mahasiswa);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function getOrangTua()
    {
        try {
            $user = Auth::User();
            $mahasiswa = $user->mahasiswa;
            if ($mahasiswa->orangTuaMahasiswa()->exists()) {
                return $this->apiResponseGet(200, 1, [$mahasiswa->orangTuaMahasiswa]);
            }
            return response()->json([
                'status' => 200,
                'recordsTotal' => 0,
                'data' => []
            ]);
        } catch (Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function storeOrangTua(Request $request)
    {
        $this->validate($request, [
            'nama_ayah' => 'string',
            'tempat_lahir_ayah' => 'string',
            'tanggal_lahir_ayah' => 'date|before:today',
            'alamat_ayah' => 'string',
            'nomor_hp_ayah' => 'string',
            'pekerjaan_ayah' => 'string',
            'penghasilan_ayah' => 'integer',
            'pekerjaan_sambilan_ayah' => 'string',
            'penghasilan_sambilan_ayah' => 'integer',
            'nama_ibu' => 'string',
            'tempat_lahir_ibu' => 'string',
            'tanggal_lahir_ibu' => 'date|before:today',
            'alamat_ibu' => 'string',
            'nomor_hp_ibu' => 'string',
            'pekerjaan_ibu' => 'string',
            'penghasilan_ibu' => 'integer',
            'pekerjaan_sambilan_ibu' => 'string',
            'penghasilan_sambilan_ibu' => 'integer',
        ]);
        try {
            $user = Auth::User();
            $mahasiswa = $user->mahasiswa;
            if (!$mahasiswa->orangTuaMahasiswa()->exists()) {
                $orangTuaMahasiswa = new OrangTuaMahasiswa;
                $orangTuaMahasiswa->mahasiswa_id = $mahasiswa->id;
                $orangTuaMahasiswa->fill($request->all());
                $orangTuaMahasiswa->save();
                return $this->apiResponse(200, 'Berhasil menambahkan orang tua', $orangTuaMahasiswa);
            }
            return $this->apiResponse(201, 'Sudah Insert Orangtua', null);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function updateOrangTua(Request $request)
    {
        // $this->validate($request, [
        //     'nama_ayah' => 'string',
        //     'tempat_lahir_ayah' => 'string',
        //     'tanggal_lahir_ayah' => 'date|before:today',
        //     'alamat_ayah' => 'string',
        //     'nomor_hp_ayah' => 'string|max:13',
        //     'pekerjaan_ayah' => 'string',
        //     'penghasilan_ayah' => 'integer',
        //     'pekerjaan_sambilan_ayah' => 'string',
        //     'penghasilan_sambilan_ayah' => 'integer',
        //     'nama_ibu' => 'string',
        //     'tempat_lahir_ibu' => 'string',
        //     'tanggal_lahir_ibu' => 'date|before:today',
        //     'alamat_ibu' => 'string',
        //     'nomor_hp_ibu' => 'string|max:13',
        //     'pekerjaan_ibu' => 'string',
        //     'penghasilan_ibu' => 'integer',
        //     'pekerjaan_sambilan_ibu' => 'string',
        //     'penghasilan_sambilan_ibu' => 'integer',
        // ]);

        $user = Auth::User();
        $mahasiswa = $user->mahasiswa;
        try {
            if ($mahasiswa->orangTuaMahasiswa()->exists()) {
                $orangTuaMahasiswa = $mahasiswa->orangTuaMahasiswa;
                $orangTuaMahasiswa->fill($request->all());
                $orangTuaMahasiswa->save();
                return $this->apiResponse(200, 'Success', $orangTuaMahasiswa);
            } else {
                $orangTuaMahasiswa = new OrangTuaMahasiswa;
                $orangTuaMahasiswa->mahasiswa_id = $mahasiswa->id;
                $orangTuaMahasiswa->fill($request->all());
                $orangTuaMahasiswa->save();
                return $this->apiResponse(200, 'Berhasil menambahkan orang tua', $orangTuaMahasiswa);
            }
            return $this->apiResponse(201, 'Belum menambahkan orangtua', null);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function getAllSaudara()
    {
        try {
            $user = Auth::User();
            $mahasiswa = $user->mahasiswa;
            return $this->apiResponseGet(200, count($mahasiswa->saudaraMahasiswa), $mahasiswa->saudaraMahasiswa);
        } catch (Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function getSaudara(Request $request)
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
        $mahasiswa = $user->mahasiswa;

        try {
            if ($request->id) {
                $saudara = SaudaraMahasiswa::where('id', $request->id)->where('mahasiswa_id', $mahasiswa->id)->get();
                $count = 1;
            } else {
                $query = SaudaraMahasiswa::where('mahasiswa_id', $mahasiswa->id)->where('nama', 'like', '%' . $search_text . '%');
                $count = $query->count();
                $saudara = $query->skip(($page - 1) * $length)->take($length)->get();
            }
            return $this->apiResponseGet(200, $count, $saudara);
        } catch (Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function storeSaudara(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|string',
            'usia' => 'required|integer|gt:0',
            'status_pernikahan' => 'required|string|in:Menikah,Belum menikah',
            'status_saudara' => 'required|string|in:Kakak,Adik',
            'status_pekerjaan' => 'required|string|in:Bekerja,Belum bekerja',
        ]);
        try {
            $user = Auth::User();
            $mahasiswa = $user->mahasiswa;
            $saudaraMahasiswa = new SaudaraMahasiswa;
            $saudaraMahasiswa->mahasiswa_id = $mahasiswa->id;
            $saudaraMahasiswa->fill($request->all());
            $saudaraMahasiswa->save();
            return $this->apiResponse(200, 'Berhasil menambahkan saudara mahasiswa', $saudaraMahasiswa);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function updateSaudara(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer',
            'nama' => 'string',
            'usia' => 'integer|gt:0',
            'status_pernikahan' => 'required|string|in:Menikah,Belum menikah',
            'status_saudara' => 'required|string|in:Kakak,Adik',
            'status_pekerjaan' => 'required|string|in:Bekerja,Belum bekerja',
        ]);
        try {
            $user = Auth::User();
            $mahasiswa = $user->mahasiswa;
            $saudaraMahasiswa = SaudaraMahasiswa::where('id', $request->id)->where('mahasiswa_id', $mahasiswa->id)->firstOrFail();
            $saudaraMahasiswa->update($request->all());
            return $this->apiResponse(200, 'Saudara mahasiswa berhasil diubah', $saudaraMahasiswa);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function destroySaudara(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);
        try {
            $user = Auth::User();
            $mahasiswa = $user->mahasiswa;
            $saudaraMahasiswa = SaudaraMahasiswa::where('id', $request->id)->where('mahasiswa_id', $mahasiswa->id)->firstOrFail();
            $saudaraMahasiswa->delete();
            return $this->apiResponse(200, 'Saudara mahasiswa berhasil dihapus', $saudaraMahasiswa);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function validasiDataMahasiswa($mahasiswa)
    {
        return (!is_null($mahasiswa->tempat_lahir) && !is_null($mahasiswa->tanggal_lahir) && !is_null($mahasiswa->gender) && !is_null($mahasiswa->semester) && !is_null($mahasiswa->ipk) && !is_null($mahasiswa->nama_bank) && !is_null($mahasiswa->nomor_rekening) && !is_null($mahasiswa->alamat) && !is_null($mahasiswa->kota) && !is_null($mahasiswa->kode_pos) && !is_null($mahasiswa->nomor_hp) && !is_null($mahasiswa->email) && !is_null($mahasiswa->angkatan) && $mahasiswa->status_keaktifan == "Aktif");
    }

    public function validasiDataSertifikat($mahasiswa)
    {
        return (!is_null($mahasiswa->sertifikat_ppkk) && !is_null($mahasiswa->sertifikat_bn) && !is_null($mahasiswa->sertifikat_metagama) && !is_null($mahasiswa->sertifikat_butterfly) && !is_null($mahasiswa->sertifikat_esq));
    }

    public function validasiBerkas($mahasiswa)
    {
        return (!is_null($mahasiswa->file_transkrip_nilai) && !is_null($mahasiswa->file_kk) && !is_null($mahasiswa->file_ktm));
    }

    public function validasiDataOrangTuaMahasiswa($mahasiswa)
    {
        if ($mahasiswa->orangTuaMahasiswa()->exists()) {
            $orangTuaMahasiswa = $mahasiswa->orangTuaMahasiswa;
            return (!is_null($orangTuaMahasiswa->nama_ayah) && !is_null($orangTuaMahasiswa->tempat_lahir_ayah) && !is_null($orangTuaMahasiswa->tanggal_lahir_ayah) && !is_null($orangTuaMahasiswa->alamat_ayah) && !is_null($orangTuaMahasiswa->nomor_hp_ayah) && !is_null($orangTuaMahasiswa->pekerjaan_ayah) && !is_null($orangTuaMahasiswa->penghasilan_ayah) && !is_null($orangTuaMahasiswa->nama_ibu) && !is_null($orangTuaMahasiswa->tempat_lahir_ibu) && !is_null($orangTuaMahasiswa->tanggal_lahir_ibu) && !is_null($orangTuaMahasiswa->alamat_ibu) && !is_null($orangTuaMahasiswa->nomor_hp_ibu) && !is_null($orangTuaMahasiswa->pekerjaan_ibu) && !is_null($orangTuaMahasiswa->penghasilan_ibu));
        }
        return false;
    }

    public function validasiWaktuPendaftaranBeasiswa($beasiswa)
    {
        return (Carbon::now()->between(Carbon::create($beasiswa->awal_pendaftaran), Carbon::create($beasiswa->akhir_pendaftaran)));
    }

    public function getBeasiswaMahasiswa($mahasiswa)
    {
        $listStatusPenerimaanBeasiswa = ['Mendaftar', 'Dinilai oleh wali kelas', 'Lulus seleksi program studi', 'Lulus seleksi jurusan', 'Menerima beasiswa'];
        foreach ($mahasiswa->beasiswa as $key => $beasiswa) {
            if (in_array($beasiswa->pivot->status, $listStatusPenerimaanBeasiswa)) {
                return $beasiswa;
            }

            // Perlu ditingkatkan
            // if(in_array($beasiswa->pivot->status, $listStatusPenerimaanBeasiswa) && Carbon::now() >= $beasiswa->awal_penerimaan && Carbon::now() <= $beasiswa->akhir_penerimaan){
            //     return $beasiswa;
            // }
        }
    }

    public function validasiStatusPendaftaranBeasiswa($beasiswa)
    {
        return ($beasiswa->status_pendaftaran == "Dibuka");
    }
    //
    //    public function getSkorIPK($ipk)
    //    {
    //        $skorIpk = 0;
    //        if ($ipk > 3.75) {
    //            $skorIpk = 4;
    //        } else if ($ipk > 3.5 && $ipk <= 3.75) {
    //            $skorIpk = 3;
    //        } else if ($ipk > 3.25 && $ipk <= 3.5) {
    //            $skorIpk = 2;
    //        } else if ($ipk >= 3.0 && $ipk <= 3.25) {
    //            $skorIpk = 1;
    //        }
    //        return $skorIpk;
    //    }
    //
    //    public function getSkorPenghasilanOrangTua($penghasilanOrangTua)
    //    {
    //        $skorPenghasilanOrangTua = 0;
    //        if ($penghasilanOrangTua <= 1500000) {
    //            $skorPenghasilanOrangTua = 4;
    //        } else if ($penghasilanOrangTua > 1500000 && $penghasilanOrangTua <= 2500000) {
    //            $skorPenghasilanOrangTua = 3;
    //        } else if ($penghasilanOrangTua > 2500000 && $penghasilanOrangTua <= 3500000) {
    //            $skorPenghasilanOrangTua = 2;
    //        } else if ($penghasilanOrangTua > 3500000 && $penghasilanOrangTua <= 5000000) {
    //            $skorPenghasilanOrangTua = 1;
    //        } else if ($penghasilanOrangTua > 5000000) {
    //            $skorPenghasilanOrangTua = 0;
    //        }
    //        return $skorPenghasilanOrangTua;
    //    }
    //
    //    public function getSkorTanggunganOrangTua($tanggunganOrangTua)
    //    {
    //        $skorTanggungan = 0;
    //        if ($tanggunganOrangTua <= 750000) {
    //            $skorTanggungan = 4;
    //        } else if ($tanggunganOrangTua > 750000 && $tanggunganOrangTua <= 1000000) {
    //            $skorTanggungan = 3;
    //        } else if ($tanggunganOrangTua > 1000000 && $tanggunganOrangTua <= 1500000) {
    //            $skorTanggungan = 2;
    //        } else if ($tanggunganOrangTua > 1500000 && $tanggunganOrangTua <= 2000000) {
    //            $skorTanggungan = 1;
    //        } else if ($tanggunganOrangTua > 2000000) {
    //            $skorTanggungan = 0;
    //        }
    //        return $skorTanggungan;
    //    }

    public function applyBeasiswa(Request $request)
    {
        $this->validate($request, [
            'beasiswa_id' => 'required',
        ]);
        try {
            $user = Auth::User();
            $mahasiswa = $user->mahasiswa;

            if (!$this->validasiDataMahasiswa($mahasiswa)) {
                return $this->apiResponse(201, 'Profil mahasiswa belum lengkap', null);
            }
            if (!$this->validasiDataOrangTuaMahasiswa($mahasiswa)) {
                return $this->apiResponse(201, 'Data orang tua mahasiswa belum lengkap', null);
            }
            if (!$this->validasiBerkas($mahasiswa)) {
                return $this->apiResponse(201, 'Berkas belum lengkap', null);
            }
            if (!$this->validasiDataSertifikat($mahasiswa)) {
                return $this->apiResponse(201, 'Sertifikat wajib belum lengkap', null);
            }
/*            $beasiswaMahasiswa = $this->getBeasiswaMahasiswa($mahasiswa);
            if ($beasiswaMahasiswa) {
                return $this->apiResponse(201, 'Sudah Mendaftar Beasiswa', null);
            }
 */           $beasiswa = Beasiswa::findOrFail($request->beasiswa_id);
            if (!$this->validasiWaktuPendaftaranBeasiswa($beasiswa)) {
                return $this->apiResponse(201, 'Waktu Pendaftaran Beasiswa Tidak Valid', null);
            }
            if (!$this->validasiStatusPendaftaranBeasiswa($beasiswa)) {
                return $this->apiResponse(201, 'Pendaftaran beasiswa sudah ditutup', null);
            }

            $kuotaProdiAngkatan = DB::table('beasiswa_program_studi')
                ->where('program_studi_id', $mahasiswa->program_studi_id)
                ->where('beasiswa_id', $request->beasiswa_id)
                ->where('angkatan', $mahasiswa->angkatan)
                ->first();
            if (!$kuotaProdiAngkatan) {
                return $this->apiResponse(201, 'Kuota Tidak Tersedia', null);
            }
            if ($mahasiswa->ipk < $beasiswa->ipk_minimal) {
                return $this->apiResponse(201, 'IPK tidak memenuhi persyaratan', null);
            }
            $orangTuaMahasiswa = $mahasiswa->orangTuaMahasiswa;
            $penghasilanOrangTua = $orangTuaMahasiswa->penghasilan_ayah + $orangTuaMahasiswa->penghasilan_sambilan_ayah + $orangTuaMahasiswa->penghasilan_ibu + $orangTuaMahasiswa->penghasilan_sambilan_ibu;
            if ($penghasilanOrangTua > $beasiswa->penghasilan_orang_tua_maksimal) {
                return $this->apiResponse(201, 'Penghasilan orang tua tidak memenuhi persyaratan', null);
            }
//            $skorIpk = $this->getSkorIPK($mahasiswa->ipk);
//            $skorPenghasilanOrangTua = $this->getSkorPenghasilanOrangTua($penghasilanOrangTua);
//            $jumlahTanggungan = 1 + $mahasiswa->saudaraMahasiswa()
//                ->where('status_pernikahan', 'Belum Menikah')
//                ->where('status_pekerjaan', 'Belum Bekerja')
//                ->count();
            //          $tanggunganOrangTua = $penghasilanOrangTua / $jumlahTanggungan;
            //        $skorTanggungan = $this->getSkorTanggunganOrangTua($tanggunganOrangTua);
            //      $skorKemampuanEkonomi = ($skorPenghasilanOrangTua + $skorTanggungan) / 2;
            $mahasiswa->beasiswa()->attach($beasiswa->id);
            return $this->apiResponse(200, 'Berhasil mendaftar beasiswa', $mahasiswa);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function storeSertifikatWajibMahasiswa(Request $request)
    {
        $this->validate($request, [
            'sertifikat_ppkk' => 'mimes:jpeg,jpg,bmp,png,gif,svg,pdf',
            'sertifikat_bn' => 'mimes:jpeg,jpg,bmp,png,gif,svg,pdf',
            'sertifikat_metagama' => 'mimes:jpeg,jpg,bmp,png,gif,svg,pdf',
            'sertifikat_butterfly' => 'mimes:jpeg,jpg,bmp,png,gif,svg,pdf',
            'sertifikat_esq' => 'mimes:jpeg,jpg,bmp,png,gif,svg,pdf',
        ]);
        try {
            $user = Auth::User();
            $mahasiswa = $user->mahasiswa;
            if ($request->hasFile('sertifikat_ppkk')) {
                if ($mahasiswa->sertifikat_ppkk) {
                    unlink(('sertifikat_wajib/' . $mahasiswa->sertifikat_ppkk));
                }
                $ppkkFileName = 'sertifikat_ppkk_' . $mahasiswa->nim . '.' . $request->sertifikat_ppkk->extension();
                $request->file('sertifikat_ppkk')->move(('sertifikat_wajib'), $ppkkFileName);
                $mahasiswa->sertifikat_ppkk = $ppkkFileName;
            }
            if ($request->hasFile('sertifikat_bn')) {
                if ($mahasiswa->sertifikat_bn) {
                    unlink(('sertifikat_wajib/' . $mahasiswa->sertifikat_bn));
                }
                $bnFileName = 'sertifikat_bn_' . $mahasiswa->nim . '.' . $request->sertifikat_bn->extension();
                $request->file('sertifikat_bn')->move(('sertifikat_wajib'), $bnFileName);
                $mahasiswa->sertifikat_bn = $bnFileName;
            }

            if ($request->hasFile('sertifikat_metagama')) {
                if ($mahasiswa->sertifikat_metagama) {
                    unlink(('sertifikat_wajib/' . $mahasiswa->sertifikat_metagama));
                }
                $metagamaFileName = 'sertifikat_metagama_' . $mahasiswa->nim . '.' . $request->sertifikat_metagama->extension();
                $request->file('sertifikat_metagama')->move(('sertifikat_wajib'), $metagamaFileName);
                $mahasiswa->sertifikat_metagama = $metagamaFileName;
            }

            if ($request->hasFile('sertifikat_esq')) {
                if ($mahasiswa->sertifikat_esq) {
                    unlink(('sertifikat_wajib/' . $mahasiswa->sertifikat_esq));
                }
                $esqFileName = 'sertifikat_esq_' . $mahasiswa->nim . '.' . $request->sertifikat_esq->extension();
                $request->file('sertifikat_esq')->move(('sertifikat_wajib'), $esqFileName);
                $mahasiswa->sertifikat_esq = $esqFileName;
            }

            if ($request->hasFile('sertifikat_butterfly')) {
                if ($mahasiswa->sertifikat_butterfly) {
                    unlink(('sertifikat_wajib/' . $mahasiswa->sertifikat_butterfly));
                }
                $butterflyFileName = 'sertifikat_butterfly_' . $mahasiswa->nim . '.' . $request->sertifikat_butterfly->extension();
                $request->file('sertifikat_butterfly')->move(('sertifikat_wajib'), $butterflyFileName);
                $mahasiswa->sertifikat_butterfly = $butterflyFileName;
            }
            $mahasiswa->save();
            return $this->apiResponse(200, 'Berkas berhasil ditambahkan', $mahasiswa);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function storeBerkasWajibMahasiswa(Request $request)
    {
        $this->validate($request, [
            'file_transkrip_nilai' => 'mimes:jpeg,jpg,bmp,png,gif,svg,pdf',
            'file_kk' => 'mimes:jpeg,jpg,bmp,png,gif,svg,pdf',
            'file_ktm' => 'mimes:jpeg,jpg,bmp,png,gif,svg,pdf',
        ]);
        try {
            $user = Auth::User();
            $mahasiswa = $user->mahasiswa;
            if ($request->hasFile('file_transkrip_nilai')) {
                if ($mahasiswa->file_transkrip_nilai) {
                    unlink(('berkas_wajib/' . $mahasiswa->file_transkrip_nilai));
                }
                $transkripNilaiFileName = 'file_transkrip_nilai_' . $mahasiswa->nim . '.' . $request->file_transkrip_nilai->extension();
                $request->file('file_transkrip_nilai')->move(('berkas_wajib'), $transkripNilaiFileName);
                $mahasiswa->file_transkrip_nilai = $transkripNilaiFileName;
            }
            if ($request->hasFile('file_kk')) {
                if ($mahasiswa->file_kk) {
                    unlink(('berkas_wajib/' . $mahasiswa->file_kk));
                }
                $kkFileName = 'file_kk_' . $mahasiswa->nim . '.' . $request->file_kk->extension();
                $request->file('file_kk')->move(('berkas_wajib'), $kkFileName);
                $mahasiswa->file_kk = $kkFileName;
            }

            if ($request->hasFile('file_ktm')) {
                if ($mahasiswa->file_ktm) {
                    unlink(('berkas_wajib/' . $mahasiswa->file_ktm));
                }
                $ktmFileName = 'file_ktm_' . $mahasiswa->nim . '.' . $request->file_ktm->extension();
                $request->file('file_ktm')->move(('berkas_wajib'), $ktmFileName);
                $mahasiswa->file_ktm = $ktmFileName;
            }
            $mahasiswa->save();
            return $this->apiResponse(200, 'success', ['mahasiswa' => $mahasiswa]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function getAllSertifikatPrestasiMahasiswa(Request $request)
    {
        try {
            $user = Auth::User();
            $mahasiswa = $user->mahasiswa;
            return $this->apiResponse(200, 'success', ['sertifikat' => $mahasiswa->sertifikatPrestasi]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function getSertifikatPrestasiMahasiswa(Request $request)
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
            $mahasiswa = $user->mahasiswa;
            if ($request->id) {
                $sertifikat = SertifikatPrestasi::where('id', $request->id)->where('mahasiswa_id', $mahasiswa->id)->get();
                $count = 1;
            } else {
                $query = SertifikatPrestasi::where('mahasiswa_id', $mahasiswa->id);

                $count = $query->count();
                $sertifikat = $query->skip(($page - 1) * $length)->take($length)->get();
            }

            return $this->apiResponseGet(200, $count, $sertifikat);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function storeSertifikatPrestasiMahasiswa(Request $request)
    {
        $this->validate($request, [
            'file_sertifikat' => 'required|mimes:jpeg,jpg,bmp,png,gif,svg,pdf',
            'tingkat_prestasi' => 'required|string|in:Internasional,Nasional,Provinsi,Kota',
        ]);
        try {
            $user = Auth::User();
            $mahasiswa = $user->mahasiswa;
            if ($request->hasFile('file_sertifikat')) {
                $filename = 'prestasi_' . $request->tingkat_prestasi . $mahasiswa->nim . '_' . time() . '.' . $request->file_sertifikat->extension();
                $request->file_sertifikat->move(('sertifikat_prestasi'), $filename);
                $sertifikatPrestasi = new SertifikatPrestasi;
                $sertifikatPrestasi->mahasiswa_id = $mahasiswa->id;
                $sertifikatPrestasi->file_sertifikat = $filename;
                $sertifikatPrestasi->tingkat_prestasi = $request->tingkat_prestasi;
                $sertifikatPrestasi->save();
            }
            return $this->apiResponse(200, 'Berhasil menambahkan sertifikat prestasi', $sertifikatPrestasi);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function updateSertifikatPrestasiMahasiswa(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
//            'file_sertifikat' => 'required|mimes:jpeg,jpg,bmp,png,gif,svg,pdf',
            'tingkat_prestasi' => 'required|string|in:Internasional,Nasional,Provinsi,Kota',
        ]);
        try {
            $user = Auth::User();
            $mahasiswa = $user->mahasiswa;
            $sertifikatPrestasi = SertifikatPrestasi::findOrFail($request->id);
            if ($request->hasFile('file_sertifikat')) {
                unlink(('sertifikat_prestasi/' . $sertifikatPrestasi->file_sertifikat));
                $filename = 'prestasi_' . $request->tingkat_prestasi . $mahasiswa->nim . '_' . time() . '.' . $request->file_sertifikat->extension();
                $request->file_sertifikat->move(('sertifikat_prestasi'), $filename);
                $sertifikatPrestasi->file_sertifikat = $filename;
            }
            $sertifikatPrestasi->tingkat_prestasi = $request->tingkat_prestasi;
            $sertifikatPrestasi->save();
            return $this->apiResponse(200, 'Berhasil mengubah sertifikat prestasi', $sertifikatPrestasi);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function destroySertifikatPrestasiMahasiswa(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer',
        ]);
        try {
            $sertifikat = SertifikatPrestasi::findOrFail($request->id);
            unlink(('sertifikat_prestasi/' . $sertifikat->file_sertifikat));
            $sertifikat->delete();
            return $this->apiResponse(200, 'Berhasil menghapus sertifikat prestasi', $sertifikat);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function getAllSertifikatOrganisasiMahasiswa(Request $request)
    {
        try {
            $user = Auth::User();
            $mahasiswa = $user->mahasiswa;
            return $this->apiResponse(200, 'success', ['sertifikat' => $mahasiswa->sertifikatOrganisasi]);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function getSertifikatOrganisasiMahasiswa(Request $request)
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
            $mahasiswa = $user->mahasiswa;
            if ($request->id) {
                $sertifikat = SertifikatOrganisasi::where('id', $request->id)->where('mahasiswa_id', $mahasiswa->id)->get();
                $count = 1;
            } else {
                $query = SertifikatOrganisasi::where('mahasiswa_id', $mahasiswa->id);

                $count = $query->count();
                $sertifikat = $query->skip(($page - 1) * $length)->take($length)->get();
            }

            return $this->apiResponseGet(200, $count, $sertifikat);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function storeSertifikatOrganisasiMahasiswa(Request $request)
    {
        $this->validate($request, [
            'file_sertifikat' => 'required|mimes:jpeg,jpg,bmp,png,gif,svg,pdf',
            'jenis' => 'required|string|in:Pengurus Organisasi,Kepanitiaan Program Kerja Kemahasiswaan',
        ]);
        try {
            $user = Auth::User();
            $mahasiswa = $user->mahasiswa;
            if ($request->hasFile('file_sertifikat')) {
                $filename = 'organisasi_' . $request->jenis . $mahasiswa->nim . '_' . time() . '.' . $request->file_sertifikat->extension();
                $request->file_sertifikat->move(('sertifikat_organisasi'), $filename);
                $sertifikatOrganisasi = new SertifikatOrganisasi;
                $sertifikatOrganisasi->mahasiswa_id = $mahasiswa->id;
                $sertifikatOrganisasi->file_sertifikat = $filename;
                $sertifikatOrganisasi->jenis = $request->jenis;
                $sertifikatOrganisasi->save();
            }
            return $this->apiResponse(200, 'Berhasil menambahkan sertifikat organisasi', $sertifikatOrganisasi);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function updateSertifikatOrganisasiMahasiswa(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            //        'file_sertifikat' => 'required|mimes:jpeg,jpg,bmp,png,gif,svg,pdf',
            'jenis' => 'required|string|in:Pengurus Organisasi,Kepanitiaan Program Kerja Kemahasiswaan',
        ]);
        try {
            $user = Auth::User();
            $mahasiswa = $user->mahasiswa;
            $sertifikatOrganisasi = SertifikatOrganisasi::findOrFail($request->id);
            if ($request->hasFile('file_sertifikat')) {
                unlink(('sertifikat_organisasi/' . $sertifikatOrganisasi->file_sertifikat));
                $filename = 'organisasi_' . $request->jenis . $mahasiswa->nim . '_' . time() . '.' . $request->file_sertifikat->extension();
                $request->file_sertifikat->move(('sertifikat_organisasi'), $filename);
                $sertifikatOrganisasi->file_sertifikat = $filename;
            }
            $sertifikatOrganisasi->jenis = $request->jenis;
            $sertifikatOrganisasi->save();
            return $this->apiResponse(200, 'Berhasil mengubah sertifikat organisasi', $sertifikatOrganisasi);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }

    public function destroySertifikatOrganisasiMahasiswa(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer',
        ]);
        try {
            $sertifikat = SertifikatOrganisasi::findOrFail($request->id);
            unlink(('sertifikat_organisasi/' . $sertifikat->file_sertifikat));
            $sertifikat->delete();
            return $this->apiResponse(200, 'Berhasil menghapus sertifikat organisasi', $sertifikat);
        } catch (\Exception $e) {
            return $this->apiResponse(201, $e->getMessage(), null);
        }
    }
}
