<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    protected $table = 'program_studi';

    public function jurusan() {
        return $this->belongsTo('App\Jurusan');
    }

    public function ketuaProgramStudi(){
    	return $this->hasOne('App\KetuaProgramStudi');
    }

    public function mahasiswa(){
    	return $this->hasMany('App\Mahasiswa');
    }    

    public function beasiswa() {
        return $this->belongsToMany('App\Beasiswa', 'beasiswa_program_studi', 'program_studi_id', 'beasiswa_id')->withPivot('angkatan', 'kuota')->withTimestamps();
    }


}
