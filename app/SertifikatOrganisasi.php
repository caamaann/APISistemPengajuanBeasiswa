<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SertifikatOrganisasi extends Model
{
    protected $table = 'sertifikat_organisasi';
    protected $fillable = ['file_sertifikat', 'jenis'];

    public function mahasiswa()
    {
        return $this->belongsTo('App\Mahasiswa');
    }
}
