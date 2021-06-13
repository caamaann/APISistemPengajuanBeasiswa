<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaudaraMahasiswa extends Model
{
    protected $table = 'saudara_mahasiswa';
    protected $fillable = ['nama', 'usia', 'status_pernikahan', 'status_saudara', 'status_pekerjaan'];

    public function mahasiswa()
    {
        return $this->belongsTo('App\Mahasiswa');
    }
}
