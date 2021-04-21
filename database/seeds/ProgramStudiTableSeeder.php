<?php

use Illuminate\Database\Seeder;
use App\ProgramStudi;

class ProgramStudiTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $programStudi = [
            ['nama' => 'D3-Teknik Konstruksi Gedung', 'jurusan_id' => 1],
            ['nama' => 'D3-Teknik Konstruksi Sipil', 'jurusan_id' => 1],
            ['nama' => 'D4-Teknik Perancangan Jalan Dan Jembatan', 'jurusan_id' => 1],
            ['nama' => 'D4-Teknik Perawatan dan Perbaikan Gedung', 'jurusan_id' => 1],
            ['nama' => 'D3-Teknik Mesin', 'jurusan_id' => 2],
            ['nama' => 'D3-Aeronautika', 'jurusan_id' => 2],
            ['nama' => 'D4-Teknik Perancangan dan Konstruksi Mesin', 'jurusan_id' => 2],
            ['nama' => 'D4-Proses Manufaktur', 'jurusan_id' => 2],
            ['nama' => 'D3-Teknik Pendingin dan Tata Udara', 'jurusan_id' => 3],
            ['nama' => 'D4-Teknik Pendingin dan Tata Udara', 'jurusan_id' => 3],
            ['nama' => 'D3-Teknik Konversi Energi', 'jurusan_id' => 4],
            ['nama' => 'D4-Teknologi Pembangkit Tenaga Listrik', 'jurusan_id' => 4],
            ['nama' => 'D4-Teknik Konservasi Energi', 'jurusan_id' => 4],
            ['nama' => 'D3-Teknik Elektronika', 'jurusan_id' => 5],
            ['nama' => 'D3-Teknik Listrik', 'jurusan_id' => 5],
            ['nama' => 'D3-Teknik Telekomunikasi', 'jurusan_id' => 5],
            ['nama' => 'D4-Teknik Elektronika', 'jurusan_id' => 5],
            ['nama' => 'D4-Teknik Otomasi Industri', 'jurusan_id' => 5],
            ['nama' => 'D4-Teknik Telekomunikasi', 'jurusan_id' => 5],
            ['nama' => 'D3-Teknik Kimia', 'jurusan_id' => 6],
            ['nama' => 'D3-Analis Kimia', 'jurusan_id' => 6],
            ['nama' => 'D4-Teknik Kimia Produksi Bersih', 'jurusan_id' => 6],
            ['nama' => 'D3-Teknik Informatika', 'jurusan_id' => 7],
            ['nama' => 'D4-Teknik Informatika', 'jurusan_id' => 7],
            ['nama' => 'D3-Akuntansi', 'jurusan_id' => 8],
            ['nama' => 'D3-Keuangan dan Perbankan', 'jurusan_id' => 8],
            ['nama' => 'D4-Akuntansi Manajemen Pemerintahan', 'jurusan_id' => 8],
            ['nama' => 'D4-Keuangan Syariah', 'jurusan_id' => 8],
            ['nama' => 'D4-Akuntansi', 'jurusan_id' => 8],
            ['nama' => 'D3-Administrasi Bisnis', 'jurusan_id' => 9],
            ['nama' => 'D3-Usaha Perjalanan Wisata', 'jurusan_id' => 9],
            ['nama' => 'D3-Manajemen Pemasaran', 'jurusan_id' => 9],
            ['nama' => 'D4-Administrasi Bisnis', 'jurusan_id' => 9],
            ['nama' => 'D4-Manajemen Aset', 'jurusan_id' => 9],
            ['nama' => 'D4-Manajemen Pemasaran', 'jurusan_id' => 9],
            ['nama' => 'D3-Bahasa Inggris', 'jurusan_id' => 10],
        ];

        foreach($programStudi as $programStudiItem){
    		ProgramStudi::create($programStudiItem);
		}
    }
}
