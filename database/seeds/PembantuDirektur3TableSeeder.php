<?php

use Illuminate\Database\Seeder;
use App\PembantuDirektur3;
use App\Role;
use App\User;

class PembantuDirektur3TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $listItem = [
            ['nama' => 'Darusi, S.Sos', 'nip' => '196504211987031004'],
        ];

        foreach($listItem as $item){
            $user = new User;
            $user->username = $item['nip'];
            $user->password = app('hash')->make($item['nip']);
            $user->save();            
            $role = Role::where('name', 'pd3')->firstOrFail();
            $user->roles()->attach($role->id);
            $item['user_id'] = $user->id;
            PembantuDirektur3::create($item);
        }
    }
}
