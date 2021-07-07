<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PerbandinganAlternatif extends Model
{
    protected $table = 'perbandingan_alternatif';
    protected $fillable = ['beasiswa_id', 'kriteria_id', 'mahasiswa_id_1', 'bobot_1', 'mahasiswa_id_1', 'bobot_2'];

}
