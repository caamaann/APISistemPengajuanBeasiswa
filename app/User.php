<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject 
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];


    //this is new

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function roles() {
        return $this->belongsToMany('App\Role', 'role_user', 'user_id', 'role_id')->withTimestamps();
    }

    public function mahasiswa(){
        return $this->hasOne('App\Mahasiswa');
    }

    public function waliKelas(){
        return $this->hasOne('App\WaliKelas');
    }

    public function ketuaProgramStudi(){
        return $this->hasOne('App\KetuaProgramStudi');
    }

    public function ketuaJurusan(){
        return $this->hasOne('App\KetuaJurusan');
    }

    public function pembantuDirektur3(){
        return $this->hasOne('App\PembantuDirektur3');
    }
}
