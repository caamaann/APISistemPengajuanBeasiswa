<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Beasiswa extends Model
{
    protected $table = 'beasiswa';
    protected $fillable = ['nama', 'deskripsi', 'gambar_beasiswa', 'ipk_minimal', 'biaya_pendidikan_per_semester', 'penghasilan_orang_tua_maksimal', 'awal_pendaftaran', 'akhir_pendaftaran', 'awal_penerimaan', 'akhir_penerimaan', 'bobot_ipk', 'bobot_prestasi', 'bobot_perilaku', 'bobot_organisasi', 'bobot_kemampuan_ekonomi'];

    public function mahasiswa() {
        return $this->belongsToMany('App\Mahasiswa', 'pendaftar_beasiswa', 'beasiswa_id', 'mahasiswa_id')->withPivot('skor_ipk', 'skor_prestasi', 'skor_perilaku', 'skor_organisasi', 'skor_kemampuan_ekonomi', 'skor_akhir', 'status')->orderBy('pivot_skor_akhir', 'desc')->withTimestamps();
    }

    public function programStudi() {
        return $this->belongsToMany('App\ProgramStudi', 'beasiswa_program_studi', 'beasiswa_id', 'program_studi_id')->withPivot('angkatan', 'kuota')->withTimestamps();
    }
}
