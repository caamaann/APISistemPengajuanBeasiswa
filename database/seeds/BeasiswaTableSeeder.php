<?php

use Illuminate\Database\Seeder;
use App\Beasiswa;

class BeasiswaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $beasiswa = [
            ['nama' => 'Peningkatan Prestasi Akademik', 'deskripsi' => 'Beasiswa PPA atau Beasiswa Peningkatan Prestasi Akademik merupakan beasiswa diberikan pemerintah melalui Kemenristekdikti', 'awal_pendaftaran' => '2019-10-20', 'akhir_pendaftaran'=>'2020-11-20', 'awal_penerimaan'=>'2020-11-20', 'akhir_penerimaan' => '2021-11-20', 'biaya_pendidikan_per_semester' => 2400000, 'ipk_minimal'=>3.2, 'penghasilan_orang_tua_maksimal'=>2000000],
            ['nama' => 'Adaro Foundation', 'deskripsi' => 'Beasiswa Adaro merupakan beasiswa yang diadakan oleh Yayasan Pelayanan Kasih AA Rahmat (YPKAAR) melalui Adaro Foundation ', 'awal_pendaftaran' => '2019-11-20', 'akhir_pendaftaran'=>'2020-12-20', 'awal_penerimaan'=>'2020-12-20', 'akhir_penerimaan' => '2021-12-20', 'biaya_pendidikan_per_semester' => 4800000, 'ipk_minimal'=>3.0, 'penghasilan_orang_tua_maksimal'=>1500000],
            ['nama' => 'bawaku', 'deskripsi' => 'Beasiswa Bawaku merupakan beasiswa untuk mahasiswa berdomisili Bandung', 'awal_pendaftaran' => '2019-10-20', 'akhir_pendaftaran'=>'2020-2-20', 'awal_penerimaan'=>'2020-2-20', 'akhir_penerimaan' => '2021-2-20', 'biaya_pendidikan_per_semester' => 1600000, 'ipk_minimal'=>3.25, 'penghasilan_orang_tua_maksimal'=>2000000],            
        ];

        foreach($beasiswa as $beasiswaItem){
    		Beasiswa::create($beasiswaItem);
		}
    }
}
