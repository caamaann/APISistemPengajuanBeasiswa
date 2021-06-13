<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KetuaProgramStudi extends Model
{
    protected $table = 'ketua_program_studi';

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function programStudi()
    {
        return $this->belongsTo('App\ProgramStudi');
    }

}
