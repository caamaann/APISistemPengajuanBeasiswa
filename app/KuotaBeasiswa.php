<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KuotaBeasiswa extends Model
{
    protected $table = 'beasiswa_program_studi';
    protected $fillable = ['beasiswa_id', 'program_studi_id', 'angkatan', 'kuota'];

}
