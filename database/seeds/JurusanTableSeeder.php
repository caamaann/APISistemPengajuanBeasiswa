<?php

use Illuminate\Database\Seeder;
use App\Jurusan;

class JurusanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jurusan = [
            ['nama' => 'Teknik Sipil'],
            ['nama' => 'Teknik Mesin'],
            ['nama' => 'Teknik Refrigerasi dan Tata Udara'],
            ['nama' => 'Teknik Konversi Energi'],
            ['nama' => 'Teknik Elektro'],
            ['nama' => 'Teknik Kimia'],
            ['nama' => 'Teknik Komputer dan Informatika'],
            ['nama' => 'Akuntansi'],
            ['nama' => 'Administrasi Niaga'],
            ['nama' => 'Bahasa Inggris'],            
        ];

        foreach($jurusan as $jurusanItem){
    		Jurusan::create($jurusanItem);
		}
    }
}
