<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $table = 'jurusan';

    public function users() {
        return $this->belongsToMany('App\User', 'role_user', 'role_id', 'user_id')->withTimestamps();
    }

    public function ketuaJurusan(){
    	return $this->hasOne('App\KetuaJurusan');	
    }

    public function waliKelas(){
        return $this->hasMany('App\WaliKelas');      
    }

    public function programStudi(){
    	return $this->hasMany('App\ProgramStudi');		
    }
}
