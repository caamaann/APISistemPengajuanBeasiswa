<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $fillable = ['nama', 'tempat_lahir', 'tanggal_lahir', 'gender', 'nama_bank', 'nomor_rekening', 'alamat', 'kota', 'kode_pos', 'nomor_hp', 'sertifikat_ppkk', 'sertifikat_bn', 'sertifikat_metagama', 'sertifikat_butterfly', 'sertifikat_esq'];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function orangTuaMahasiswa(){
    	return $this->hasOne('App\OrangTuaMahasiswa');	
    }

    public function waliKelas(){
    	return $this->belongsTo('App\WaliKelas');	
    }

    public function programStudi(){
    	return $this->belongsTo('App\ProgramStudi');	
    }

    public function beasiswa() {
        return $this->belongsToMany('App\Beasiswa', 'pendaftar_beasiswa', 'mahasiswa_id', 'beasiswa_id')->withPivot('skor_ipk', 'skor_prestasi', 'skor_perilaku', 'skor_organisasi', 'skor_kemampuan_ekonomi', 'skor_akhir', 'status')->orderBy('pivot_skor_akhir', 'desc')->withTimestamps();
    }

    public function saudaraMahasiswa(){
        return $this->hasMany('App\SaudaraMahasiswa');  
    }

    public function sertifikatPrestasi()
    {
        return $this->hasMany('App\SertifikatPrestasi');  
    }

    public function sertifikatOrganisasi()
    {
        return $this->hasMany('App\SertifikatOrganisasi');  
    }

}
