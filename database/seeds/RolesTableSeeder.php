<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['display_name' => 'Mahasiswa', 'name' => 'mahasiswa'],
            ['display_name' => 'Wali Kelas', 'name' => 'waliKelas'],
            ['display_name' => 'Ketua Program Studi', 'name' => 'ketuaProdi'],
            ['display_name' => 'Ketua Jurusan', 'name' => 'ketuaJurusan'],
            ['display_name' => 'Pembantu Direktur Bidang Kemahasiswaan', 'name' => 'pd3'],
            ['display_name' => 'Administrator', 'name' => 'admin'],
        ];
        foreach($roles as $role){
    		Role::create($role);
		}
    }
}
