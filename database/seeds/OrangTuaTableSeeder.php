<?php

use Illuminate\Database\Seeder;
use App\OrangTuaMahasiswa;

class OrangTuaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orangTua = [
            ['nama_ayah' => 'Jaenudin', 'tempat_lahir_ayah' => 'Cirebon', 'tanggal_lahir_ayah' => '1960-11-20', 'alamat_ayah' => 'Jakarta', 'nomor_hp_ayah' => '087712345', 'pekerjaan_ayah' => 'Pedagang', 'penghasilan_ayah' => 200000, 'pekerjaan_sambilan_ayah' => 'Tidak ada', 'penghasilan_sambilan_ayah' => 0, 'nama_ibu' => 'Uun', 'tempat_lahir_ibu' => 'Cirebon', 'tanggal_lahir_ibu' => '1961-11-20', 'alamat_ibu' => 'Jakarta', 'nomor_hp_ibu' => '0877123456', 'pekerjaan_ibu' => 'Tidak ada', 'penghasilan_ibu' => 0, 'pekerjaan_sambilan_ibu' => 'Tidak ada', 'penghasilan_sambilan_ibu' => 0, 'mahasiswa_id' => 1],
            ['nama_ayah' => 'Syarif', 'tempat_lahir_ayah' => 'Bandung', 'tanggal_lahir_ayah' => '1961-12-20', 'alamat_ayah' => 'Cimahi', 'nomor_hp_ayah' => '08771234', 'pekerjaan_ayah' => 'PNS', 'penghasilan_ayah' => 300000, 'pekerjaan_sambilan_ayah' => 'Tidak ada', 'penghasilan_sambilan_ayah' => 0, 'nama_ibu' => 'Nur', 'tempat_lahir_ibu' => 'Cimahi', 'tanggal_lahir_ibu' => '1963-11-20', 'alamat_ibu' => 'Cimahi', 'nomor_hp_ibu' => '08771256', 'pekerjaan_ibu' => 'Tidak ada', 'penghasilan_ibu' => 0, 'pekerjaan_sambilan_ibu' => 'Tidak ada', 'penghasilan_sambilan_ibu' => 0, 'mahasiswa_id' => 2],
            ['nama_ayah' => 'Samsul', 'tempat_lahir_ayah' => 'Bandung', 'tanggal_lahir_ayah' => '1960-11-24', 'alamat_ayah' => 'Bandung', 'nomor_hp_ayah' => '0877123415', 'pekerjaan_ayah' => 'PNS', 'penghasilan_ayah' => 200000, 'pekerjaan_sambilan_ayah' => 'Pedagang', 'penghasilan_sambilan_ayah' => 750000, 'nama_ibu' => 'Hasanah', 'tempat_lahir_ibu' => 'Bandung', 'tanggal_lahir_ibu' => '1961-11-10', 'alamat_ibu' => 'Bandung', 'nomor_hp_ibu' => '08771234956', 'pekerjaan_ibu' => 'Tidak ada', 'penghasilan_ibu' => 0, 'pekerjaan_sambilan_ibu' => 'Tidak ada', 'penghasilan_sambilan_ibu' => 0, 'mahasiswa_id' => 3],
            ['nama_ayah' => 'Mahmud', 'tempat_lahir_ayah' => 'Bandung', 'tanggal_lahir_ayah' => '1960-11-20', 'alamat_ayah' => 'Bandung', 'nomor_hp_ayah' => '0877111234', 'pekerjaan_ayah' => 'Wiraswasta', 'penghasilan_ayah' => 200000, 'pekerjaan_sambilan_ayah' => 'Tidak ada', 'penghasilan_sambilan_ayah' => 0, 'nama_ibu' => 'Aan', 'tempat_lahir_ibu' => 'Bandung', 'tanggal_lahir_ibu' => '1962-1-20', 'alamat_ibu' => 'Bandung', 'nomor_hp_ibu' => '081771256', 'pekerjaan_ibu' => 'Tidak ada', 'penghasilan_ibu' => 0, 'pekerjaan_sambilan_ibu' => 'Tidak ada', 'penghasilan_sambilan_ibu' => 0, 'mahasiswa_id' => 4],
            ['nama_ayah' => 'Bilal', 'tempat_lahir_ayah' => 'Cirebon', 'tanggal_lahir_ayah' => '1970-11-20', 'alamat_ayah' => 'Cirebon', 'nomor_hp_ayah' => '0877112345', 'pekerjaan_ayah' => 'Karyawan', 'penghasilan_ayah' => 200000, 'pekerjaan_sambilan_ayah' => 'Tidak ada', 'penghasilan_sambilan_ayah' => 0, 'nama_ibu' => 'Ani', 'tempat_lahir_ibu' => 'Cirebon', 'tanggal_lahir_ibu' => '1961-11-20', 'alamat_ibu' => 'Cirebon', 'nomor_hp_ibu' => '08771231456', 'pekerjaan_ibu' => 'Tidak ada', 'penghasilan_ibu' => 0, 'pekerjaan_sambilan_ibu' => 'Tidak ada', 'penghasilan_sambilan_ibu' => 0, 'mahasiswa_id' => 5],
            ['nama_ayah' => 'Syarif', 'tempat_lahir_ayah' => 'Cirebon', 'tanggal_lahir_ayah' => '1961-11-20', 'alamat_ayah' => 'Cirebon', 'nomor_hp_ayah' => '08771234', 'pekerjaan_ayah' => 'Pedagang', 'penghasilan_ayah' => 200000, 'pekerjaan_sambilan_ayah' => 'Tidak ada', 'penghasilan_sambilan_ayah' => 0, 'nama_ibu' => 'Sri', 'tempat_lahir_ibu' => 'Cirebon', 'tanggal_lahir_ibu' => '1962-11-24', 'alamat_ibu' => 'Cirebon', 'nomor_hp_ibu' => '087712516', 'pekerjaan_ibu' => 'Tidak ada', 'penghasilan_ibu' => 0, 'pekerjaan_sambilan_ibu' => 'Tidak ada', 'penghasilan_sambilan_ibu' => 0, 'mahasiswa_id' => 6],
            ['nama_ayah' => 'Barnas', 'tempat_lahir_ayah' => 'Bandung', 'tanggal_lahir_ayah' => '1967-1-2', 'alamat_ayah' => 'Bandung', 'nomor_hp_ayah' => '08771234115', 'pekerjaan_ayah' => 'PNS', 'penghasilan_ayah' => 200000, 'pekerjaan_sambilan_ayah' => 'Tidak ada', 'penghasilan_sambilan_ayah' => 0, 'nama_ibu' => 'Rina', 'tempat_lahir_ibu' => 'Bandung', 'tanggal_lahir_ibu' => '1973-1-14', 'alamat_ibu' => 'Bandung', 'nomor_hp_ibu' => '0877123416', 'pekerjaan_ibu' => 'Wiraswasta', 'penghasilan_ibu' => 200000, 'pekerjaan_sambilan_ibu' => 'Tidak ada', 'penghasilan_sambilan_ibu' => 0, 'mahasiswa_id' => 7],
            ['nama_ayah' => 'Damar', 'tempat_lahir_ayah' => 'Cimahi', 'tanggal_lahir_ayah' => '1962-11-2', 'alamat_ayah' => 'Cimahi', 'nomor_hp_ayah' => '0877123412', 'pekerjaan_ayah' => 'PNS', 'penghasilan_ayah' => 2000000, 'pekerjaan_sambilan_ayah' => 'Tidak ada', 'penghasilan_sambilan_ayah' => 0, 'nama_ibu' => 'Rani', 'tempat_lahir_ibu' => 'Cimahi', 'tanggal_lahir_ibu' => '1968-11-20', 'alamat_ibu' => 'Cimahi', 'nomor_hp_ibu' => '0871271256', 'pekerjaan_ibu' => 'Tidak ada', 'penghasilan_ibu' => 0, 'pekerjaan_sambilan_ibu' => 'Tidak ada', 'penghasilan_sambilan_ibu' => 0, 'mahasiswa_id' => 8],
        ];
        foreach($orangTua as $orangTuaItem){
    		OrangTuaMahasiswa::create($orangTuaItem);
		}
    }
}
