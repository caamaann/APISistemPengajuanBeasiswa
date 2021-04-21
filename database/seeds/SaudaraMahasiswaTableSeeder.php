<?php

use Illuminate\Database\Seeder;
use App\SaudaraMahasiswa;

class SaudaraMahasiswaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $saudaraMahasiswa = [
            ['nama' => 'Fajar Sidik', 'usia' => 25, 'status_saudara' => "Kakak", 'status_pernikahan' => "Belum menikah", 'status_pekerjaan' => "Bekerja", 'mahasiswa_id' => 1],
            ['nama' => 'Iin', 'usia' => 28, 'status_saudara' => "Kakak", 'status_pernikahan' => "Belum menikah", 'status_pekerjaan' => "Belum bekerja", 'mahasiswa_id' => 1],
            ['nama' => 'Kaka niki', 'usia' => 26, 'status_saudara' => "Kakak", 'status_pernikahan' => "Belum menikah", 'status_pekerjaan' => "Bekerja", 'mahasiswa_id' => 2],
            ['nama' => 'Adik niki', 'usia' => 15, 'status_saudara' => "Adik", 'status_pernikahan' => "Belum menikah", 'status_pekerjaan' => "Belum Bekerja", 'mahasiswa_id' => 2],
        ];
        foreach($saudaraMahasiswa as $saudaraMahasiswaItem){
    		SaudaraMahasiswa::create($saudaraMahasiswaItem);
		}

    }
}
