<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KetuaJurusan extends Model
{
    protected $table = 'ketua_jurusan';

    public function user() {
        return $this->belongsTo('App\User');
    }
    
    public function jurusan(){
    	return $this->belongsTo('App\Jurusan');
    }

}
