<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PerbandinganKriteria extends Model
{
    protected $table = 'perbandingan_kriteria';
    protected $fillable = ['kriteria_1', 'bobot_1','kriteria_2', 'bobot_2'];

}
