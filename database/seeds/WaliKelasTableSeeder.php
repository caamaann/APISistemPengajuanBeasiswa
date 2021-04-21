<?php

use Illuminate\Database\Seeder;
use App\WaliKelas;
use App\User;
use App\Role;

class WaliKelasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $listItem = [
            ['nama' => 'Dra. Nurjannah Syakrani, MT.', 'jurusan_id' => 7, 'nip' => '196312131992012001'],
            ['nama' => 'Jonner Hutahaean, BSET., M.Info.Sys', 'jurusan_id' => 7, 'nip' => '196210211993031002'],
            ['nama' => 'Drs. Eddy Bambang Soewono, M.Kom.', 'jurusan_id' => 7, 'nip' => '196101141992021001'],
            ['nama' => 'Ir. Irawan Thamrin, MT', 'jurusan_id' => 7, 'nip' => '196208151990031001'],
            ['nama' => 'Drs. Usmani, M.Si', 'jurusan_id' => 8, 'nip' => '195711121988031001'],
            ['nama' => 'Drs. Ahmad Syarief, SE., M.Si.', 'jurusan_id' => 8, 'nip' => '195710011995121001'],
        ];

        foreach($listItem as $item){
            $user = new User;
            $user->username = $item['nip'];
            $user->password = app('hash')->make($item['nip']);
            $user->save();            
            $role = Role::where('name', 'waliKelas')->firstOrFail();
            $user->roles()->attach($role->id);
            $item['user_id'] = $user->id;
            WaliKelas::create($item);
        }
    }
}
