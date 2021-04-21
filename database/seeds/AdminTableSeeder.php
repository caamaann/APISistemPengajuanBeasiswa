<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {              
        $user = new User;
        $user->username = 'admin';
        $user->password = app('hash')->make('admin');
        $user->save();
        $role = Role::where('name', 'admin')->firstOrFail();
        $user->roles()->attach($role->id);
    }
}
