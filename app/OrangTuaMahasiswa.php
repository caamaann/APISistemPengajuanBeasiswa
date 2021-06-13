<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrangTuaMahasiswa extends Model
{
    protected $table = 'orang_tua_mahasiswa';
    protected $fillable = ['nama_ayah', 'tempat_lahir_ayah', 'tanggal_lahir_ayah', 'alamat_ayah', 'nomor_hp_ayah', 'pekerjaan_ayah', 'penghasilan_ayah', 'pekerjaan_sambilan_ayah', 'penghasilan_sambilan_ayah', 'nama_ibu', 'tempat_lahir_ibu', 'tanggal_lahir_ibu', 'alamat_ibu', 'nomor_hp_ibu', 'pekerjaan_ibu', 'penghasilan_ibu', 'pekerjaan_sambilan_ibu', 'penghasilan_sambilan_ibu'];

    public function mahasiswa()
    {
        return $this->belongsTo('App\OrangTuaMahasiswa');
    }
}
