<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);        
        $this->call(JurusanTableSeeder::class);
        $this->call(ProgramStudiTableSeeder::class);
        $this->call(AdminTableSeeder::class);
        $this->call(PembantuDirektur3TableSeeder::class);        
        $this->call(KetuaJurusanTableSeeder::class);
        $this->call(KetuaProgramStudiTableSeeder::class);
        $this->call(WaliKelasTableSeeder::class);
        $this->call(MahasiswaTableSeeder::class);
        $this->call(OrangTuaTableSeeder::class);
        $this->call(SaudaraMahasiswaTableSeeder::class);
        $this->call(BeasiswaTableSeeder::class);
        $this->call(BeasiswaProgramStudiTableSeeder::class);
        $this->call(PendaftarBeasiswaTableSeeder::class);
    }
}
