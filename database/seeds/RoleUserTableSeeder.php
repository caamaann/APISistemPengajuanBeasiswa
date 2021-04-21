<?php

use Illuminate\Database\Seeder;
use App\User;

class RoleUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // mahasiswa        
        for ($i=1; $i <= 3; $i++) { 
            $user =  User::findOrFail($i);
            $roles_id = [1];
            $user->roles()->attach($roles_id);
        }

        // wali kelas
        for ($i=4; $i <= 6; $i++) { 
            $user =  User::findOrFail($i);
            $roles_id = [2];
            $user->roles()->attach($roles_id);
        }

        // ketua prodi
        for ($i=7; $i <= 8; $i++) { 
            $user =  User::findOrFail($i);
            $roles_id = [3];
            $user->roles()->attach($roles_id);
        }

        // ketua jurusan
        for ($i=9; $i <= 10; $i++) { 
            $user =  User::findOrFail($i);
            $roles_id = [4];
            $user->roles()->attach($roles_id);
        }

        // pd 3
        for ($i=11; $i <= 11; $i++) { 
            $user =  User::findOrFail($i);
            $roles_id = [5];
            $user->roles()->attach($roles_id);
        }

        // admin
        for ($i=12; $i <= 12; $i++) { 
            $user =  User::findOrFail($i);
            $roles_id = [6];
            $user->roles()->attach($roles_id);
        }


    }
}
