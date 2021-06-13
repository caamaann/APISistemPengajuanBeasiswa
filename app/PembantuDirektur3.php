<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PembantuDirektur3 extends Model
{
    protected $table = 'pembantu_direktur_3';

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
