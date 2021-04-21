<?php

use Illuminate\Database\Seeder;
use App\Mahasiswa;
use App\Role;
use App\User;

class MahasiswaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        
        
        $listItem = [
            ['nim' => '171511019', 'nama' => 'Wahyu', 'wali_kelas_id' => 1, 'program_studi_id' => 23, 'tempat_lahir' => 'Cirebon', 'tanggal_lahir' => '1999-6-25', 'gender' => 'l', 'semester' => 4, 'ipk' => 3.6, 'nama_bank' => 'BRI', 'nomor_rekening' => '154901016673509', 'alamat' => 'durajaya kec. Greged', 'kota' => 'cirebon', 'kode_pos' => '45172', 'nomor_hp' => '087712345678', 'email' => 'wahyaumau@gmail.com', 'angkatan' =>2017],
            ['nim' => '171511060', 'nama' => 'Nikita', 'wali_kelas_id' => 2, 'program_studi_id' => 23, 'tempat_lahir' => 'Cimahi', 'tanggal_lahir' => '1998-6-25', 'gender' => 'p', 'semester' => 5, 'ipk' => 3.2, 'nama_bank' => 'BRI', 'nomor_rekening' => '154901016674123', 'alamat' => 'Cimahi', 'kota' => 'Cimahi', 'kode_pos' => '45175', 'nomor_hp' => '0877123223', 'email' => 'nikita@gmail.com', 'angkatan' =>2017],
            ['nim' => '171524012', 'nama' => 'Ilham Gibran', 'wali_kelas_id' => 3, 'program_studi_id' => 24, 'tempat_lahir' => 'Bandung', 'tanggal_lahir' => '1998-6-25', 'gender' => 'l', 'semester' => 4, 'ipk' => 3.8, 'nama_bank' => 'BRI', 'nomor_rekening' => '154901017765381', 'alamat' => 'Cimahi', 'kota' => 'Cimahi', 'kode_pos' => '40551', 'nomor_hp' => '087712355223', 'email' => 'gibran@gmail.com', 'angkatan' =>2017],
            ['nim' => '181511032', 'nama' => 'Salma', 'wali_kelas_id' => 4, 'program_studi_id' => 23, 'tempat_lahir' => 'Bandung', 'tanggal_lahir' => '1998-6-26', 'gender' => 'p', 'semester' => 4, 'ipk' => 3.6, 'nama_bank' => 'BRI', 'nomor_rekening' => '154901017765376', 'alamat' => 'Bandung', 'kota' => 'cirebon', 'kode_pos' => '40551', 'nomor_hp' => '087712355263', 'email' => 'salma.meldiyana.tif18@polban.ac.id', 'angkatan' =>2018],
            ['nim' => '175134023', 'nama' => 'Rifa', 'wali_kelas_id' => 4, 'program_studi_id' => 27, 'tempat_lahir' => 'Cirebon', 'tanggal_lahir' => '1998-6-25', 'gender' => 'p', 'semester' => 4, 'ipk' => 3.6, 'nama_bank' => 'BRI', 'nomor_rekening' => '154901017765384', 'alamat' => 'Sumber', 'kota' => 'cirebon', 'kode_pos' => '45172', 'nomor_hp' => '087712355261', 'email' => 'rifaah.al.amp17@polban.ac.id', 'angkatan' =>2017],
            ['nim' => '175111005', 'nama' => 'Bilkis', 'wali_kelas_id' => 5, 'program_studi_id' => 25, 'tempat_lahir' => 'Cirebon', 'tanggal_lahir' => '1999-10-27', 'gender' => 'p', 'semester' => 4, 'ipk' => 3.6, 'nama_bank' => 'BRI', 'nomor_rekening' => '154901017761384', 'alamat' => 'Lemah Abang', 'kota' => 'Cirebon', 'kode_pos' => '45172', 'nomor_hp' => '087712355261', 'email' => 'bilkisti.auliya.akun17@polban.ac.id', 'angkatan' =>2017],    
            ['nim' => '171511026', 'nama' => 'Rifqi Okta', 'wali_kelas_id' => 1, 'program_studi_id' => 23, 'tempat_lahir' => 'Bandung', 'tanggal_lahir' => '1998-10-25', 'gender' => 'l', 'semester' => 5, 'ipk' => 3.3, 'nama_bank' => 'BRI', 'nomor_rekening' => '154901016673519', 'alamat' => 'Pasir Koja', 'kota' => 'Bandung', 'kode_pos' => '40551', 'nomor_hp' => '087712345645', 'email' => 'rifqi@gmail.com', 'angkatan' =>2017],
            ['nim' => '171511023', 'nama' => 'Refdinal', 'wali_kelas_id' => 1, 'program_studi_id' => 23, 'tempat_lahir' => 'Cimahi', 'tanggal_lahir' => '1998-2-21', 'gender' => 'l', 'semester' => 5, 'ipk' => 3.3, 'nama_bank' => 'BRI', 'nomor_rekening' => '154901016674509', 'alamat' => 'Cimahi', 'kota' => 'Cimahi', 'kode_pos' => '40552', 'nomor_hp' => '08771234576', 'email' => 'refdinal@gmail.com', 'angkatan' =>2017],
        ];

        foreach($listItem as $item){
            $user = new User;
            $user->username = $item['nim'];
            $user->password = app('hash')->make($item['nim']);
            $user->save();
            $role = Role::where('name', 'mahasiswa')->firstOrFail();
            $user->roles()->attach($role->id);
            $item['user_id'] = $user->id;
            $item['sertifikat_ppkk'] = 'sertifikat_ppkk_'.$item['nim'].'.jpeg';
            $item['sertifikat_bn'] = 'sertifikat_bn_'.$item['nim'].'.jpeg';
            $item['sertifikat_metagama'] = 'sertifikat_metagama_'.$item['nim'].'.jpeg';
            $item['sertifikat_butterfly'] = 'sertifikat_butterfly_'.$item['nim'].'.jpeg';
            $item['sertifikat_esq'] = 'sertifikat_esq_'.$item['nim'].'.jpeg';
            $item['sertifikat_bn'] = 'sertifikat_bn_'.$item['nim'].'.jpeg';
            $item['file_ktm'] = 'file_ktm_'.$item['nim'].'.jpeg';
            $item['file_kk'] = 'file_kk_'.$item['nim'].'.jpeg';
            $item['file_transkrip_nilai'] = 'file_transkrip_nilai_'.$item['nim'].'.jpeg';
            Mahasiswa::create($item);
        }
    }
}
