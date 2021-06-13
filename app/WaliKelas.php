<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WaliKelas extends Model
{
    protected $table = 'wali_kelas';

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function mahasiswa()
    {
        return $this->hasMany('App\Mahasiswa');
    }

    public function jurusan()
    {
        return $this->belongsTo('App\Jurusan');
    }

}
