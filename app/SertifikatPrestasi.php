<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SertifikatPrestasi extends Model
{
    protected $table = 'sertifikat_prestasi';    
    protected $fillable = ['file_sertifikat', 'tingkat_prestasi'];

    public function mahasiswa() {
        return $this->belongsTo('App\Mahasiswa');
    }
}
