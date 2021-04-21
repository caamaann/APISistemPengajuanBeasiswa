<?php

use Illuminate\Database\Seeder;
use App\Mahasiswa;

class PendaftarBeasiswaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $listPendaftarBeasiswa = [
            ["mahasiswa_id"=>1,"beasiswa_id"=>1,"skor_ipk"=>3,"skor_prestasi"=>1,"skor_perilaku"=>1,"skor_organisasi"=>1,"skor_kemampuan_ekonomi"=>4,"skor_akhir"=>2.3,"status"=>"Dinilai oleh wali kelas","created_at"=>"2019-12-09 15:27:13","updated_at"=>"2019-12-09 15:30:09"],
            ["mahasiswa_id"=>2,"beasiswa_id"=>1,"skor_ipk"=>1,"skor_prestasi"=>2,"skor_perilaku"=>2,"skor_organisasi"=>2,"skor_kemampuan_ekonomi"=>4,"skor_akhir"=>1.7,"status"=>"Dinilai oleh wali kelas","created_at"=>"2019-12-09 15:27:39","updated_at"=>"2019-12-09 15:32:04"],
            ["mahasiswa_id"=>3,"beasiswa_id"=>1,"skor_ipk"=>4,"skor_prestasi"=>2,"skor_perilaku"=>2,"skor_organisasi"=>2,"skor_kemampuan_ekonomi"=>4,"skor_akhir"=>3.2,"status"=>"Dinilai oleh wali kelas","created_at"=>"2019-12-09 15:32:58","updated_at"=>"2019-12-09 15:35:47"],
            ["mahasiswa_id"=>4,"beasiswa_id"=>1,"skor_ipk"=>3,"skor_prestasi"=>2,"skor_perilaku"=>2,"skor_organisasi"=>2,"skor_kemampuan_ekonomi"=>4,"skor_akhir"=>2.7,"status"=>"Dinilai oleh wali kelas","created_at"=>"2019-12-09 15:33:35","updated_at"=>"2019-12-09 15:36:30"],
            ["mahasiswa_id"=>7,"beasiswa_id"=>1,"skor_ipk"=>2,"skor_prestasi"=>2,"skor_perilaku"=>2,"skor_organisasi"=>0,"skor_kemampuan_ekonomi"=>4,"skor_akhir"=>2.1,"status"=>"Dinilai oleh wali kelas","created_at"=>"2019-12-09 15:28:42","updated_at"=>"2019-12-09 15:30:25"],
            ["mahasiswa_id"=>8,"beasiswa_id"=>1,"skor_ipk"=>2,"skor_prestasi"=>0,"skor_perilaku"=>4,"skor_organisasi"=>4,"skor_kemampuan_ekonomi"=>2,"skor_akhir"=>1.6,"status"=>"Dinilai oleh wali kelas","created_at"=>"2019-12-09 15:28:04","updated_at"=>"2019-12-09 15:30:46"],
            ["mahasiswa_id"=>1,"beasiswa_id"=>3,"skor_ipk"=>3,"skor_prestasi"=>2,"skor_perilaku"=>2,"skor_organisasi"=>2,"skor_kemampuan_ekonomi"=>4,"skor_akhir"=>2.7,"status"=>"Dinilai oleh wali kelas","created_at"=>"2019-12-09 15:27:20","updated_at"=>"2019-12-09 15:31:01"],
            ["mahasiswa_id"=>3,"beasiswa_id"=>3,"skor_ipk"=>4,"skor_prestasi"=>2,"skor_perilaku"=>2,"skor_organisasi"=>2,"skor_kemampuan_ekonomi"=>4,"skor_akhir"=>3.2,"status"=>"Dinilai oleh wali kelas","created_at"=>"2019-12-09 15:33:08","updated_at"=>"2019-12-09 15:36:03"],
            ["mahasiswa_id"=>4,"beasiswa_id"=>3,"skor_ipk"=>3,"skor_prestasi"=>2,"skor_perilaku"=>2,"skor_organisasi"=>2,"skor_kemampuan_ekonomi"=>4,"skor_akhir"=>2.7,"status"=>"Dinilai oleh wali kelas","created_at"=>"2019-12-09 15:33:42","updated_at"=>"2019-12-09 15:36:42"],
            ["mahasiswa_id"=>7,"beasiswa_id"=>3,"skor_ipk"=>2,"skor_prestasi"=>2,"skor_perilaku"=>1,"skor_organisasi"=>3,"skor_kemampuan_ekonomi"=>4,"skor_akhir"=>2.2,"status"=>"Dinilai oleh wali kelas","created_at"=>"2019-12-09 15:28:49","updated_at"=>"2019-12-09 15:31:19"],
            ["mahasiswa_id"=>8,"beasiswa_id"=>3,"skor_ipk"=>2,"skor_prestasi"=>2,"skor_perilaku"=>1,"skor_organisasi"=>3,"skor_kemampuan_ekonomi"=>2,"skor_akhir"=>2,"status"=>"Dinilai oleh wali kelas","created_at"=>"2019-12-09 15:28:12","updated_at"=>"2019-12-09 15:31:33"]
        ];

        foreach ($listPendaftarBeasiswa as $key => $value) {
            DB::table('pendaftar_beasiswa')->insert($value);            
        }
        
    }
}
