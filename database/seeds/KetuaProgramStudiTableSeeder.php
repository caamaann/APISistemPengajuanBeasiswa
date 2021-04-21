<?php

use Illuminate\Database\Seeder;
use App\KetuaProgramStudi;
use App\User;
use App\Role;
use App\ProgramStudi;

class KetuaProgramStudiTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $listItem = [
            // Teknik Sipil
            ['nama' => 'FISCA IGUSTIANY,SST.,MT', 'nip' => '198208042010122003'],
            ['nama' => 'ANGGA MARDITAMA SULTAN SUFANIR, ST., MT.', 'nip' => '198403062009121004'],
            ['nama' => 'Risna Rismiana Sari, S.T., M.Sc', 'nip' => '198502282012122001'],
            ['nama' => 'Ery Radya Juarti, ST., MT.', 'nip' => '198212142008122004'],

            // Teknik Mesin
            ['nama' => 'Prasetyo, ST., M.Eng.', 'nip' => '198003242008121004'],
            ['nama' => 'Mochammad Luthfi, Dipl. Ing., M.T.', 'nip' => '196309281991021001'],
            ['nama' => 'Heri Widiantoro, SST., M.Eng ', 'nip' => '198304162008121003'],
            ['nama' => 'Aris Suryadi, ST .,MT ', 'nip' => '196503211990121001'],

            // Teknik Refrigerasi dan Tata Udara
            ['nama' => 'Susilawati,S.T., M.Eng.', 'nip' => '198210092012122003'],
            ['nama' => 'M. Nuriyadi, ST., M.Eng.', 'nip' => '197211072006041001'],

            // Teknik Konversi Energi
            ['nama' => 'Sri Utami, SST., M.T.', 'nip' => '198202262010122005'],
            ['nama' => 'Siti Saodah, S.T., M.T.', 'nip' => '197711272010122002'],
            ['nama' => 'Yanti Suprianti, S.T., M.T.', 'nip' => '198101202012122001'],

            // Teknik Elektro
            ['nama' => 'Dadan Nurdin Bagenda,S.T., M.T.', 'nip' => '198510092015041003'],
            ['nama' => 'Supriyanto, ST., MT', 'nip' => '196305201988111001'],
            ['nama' => 'Mohammad Farid Susanto, ST., M.Eng.', 'nip' => '196001121988111001'],
            ['nama' => 'Feriyonika, S.T., M.Sc.Eng.', 'nip' => '198506092012121006'],
            ['nama' => 'Sarjono Wahyu Jadmiko, ST., M.Eng.', 'nip' => '196012191993031002'],
            ['nama' => 'Tata Supriyadi DUT., ST., M.Eng.', 'nip' => '196311261993031002'],

            // Teknik Kimia
            ['nama' => 'Rispiandi, ST., MT.', 'nip' => '196910161995121001'],
            ['nama' => 'Lina Troskialina, B.Sc. M.Sc., Ph.D.', 'nip' => '196505011994032001'],
            ['nama' => 'Ir. Herawati Budiastuti, M.Eng.Sc., Ph.D.', 'nip' => '196004141988112001'],

            // Teknik Komputer dan Informatika
            ['nama' => 'Ghifari Munawar,S.Kom., M.T.', 'nip' => '198604122014041001'],
            ['nama' => 'Santi Sundari, S.Si., M.T.', 'nip' => '197109031999032001'],

            // Akuntansi
            ['nama' => 'Etti Ernita Sembiring, S.E., M.Si', 'nip' => '198111062006042001'],
            ['nama' => 'Djoni Djatnika, SE., M.M.', 'nip' => '195901011993031001'],
            ['nama' => 'Dr. IRA NOVIANTYM.Si', 'nip' => '197611162009122002'],
            ['nama' => 'Dra. Kristianingsih, M.Si.', 'nip' => '196211041989032001'],
            ['nama' => 'Iyeh Supriatna, SE. Ak.., M.Si', 'nip' => '196308181991031002'],

            // Administrasi Niaga
            ['nama' => 'Dra. Sholihati Amalia, S.Sos., M.Pd.', 'nip' => '195803031986032001'],
            ['nama' => 'Dr. Marceilla Suryana, BA(Hons)., MM.Par.', 'nip' => '197608262003122002'],
            ['nama' => 'Lina Setiawati, M.A.B', 'nip' => '199207302018032001'],
            ['nama' => 'Dra. Tintin Suhaeni,S.Sos., M.Si', 'nip' => '196203301989032001'],
            ['nama' => 'Dr. Aceng Gima Sugiama, SE. MP', 'nip' => '196109161990031001'],
            ['nama' => 'Drs. Agustinus C. Februadi, M.Phil', 'nip' => '196002081990031002'],

            // Bahasa Inggris
            // ['nama' => 'Drs. Saudin, M.Pd', 'nip' => '195904101989031001'],
        ];

        $i = 1;
        foreach($listItem as $item){
            $user = new User;
            $user->username = $item['nip'];
            $user->password = app('hash')->make($item['nip']);
            $user->save();
            $role = Role::where('name', 'ketuaProdi')->firstOrFail();
            $user->roles()->attach($role->id);
            $item['user_id'] = $user->id;
            $item['program_studi_id'] = $i;
            KetuaProgramStudi::create($item);
            $i++;
        }

        $userKajurBahasaInggris = User::where('username', '195904101989031001')->firstOrFail();
        $role = Role::where('name', 'ketuaProdi')->firstOrFail();
        $userKajurBahasaInggris->roles()->sync($role->id);
        $prodi = ProgramStudi::where('nama', 'D3-Bahasa Inggris')->firstOrFail();
        $kaProdiBahasaInggris = new KetuaProgramStudi;
        $kaProdiBahasaInggris->fill(['nama' => 'Drs. Saudin, M.Pd', 'nip' => '195904101989031001']);
        $kaProdiBahasaInggris['user_id'] = $userKajurBahasaInggris->id;
        $kaProdiBahasaInggris['program_studi_id'] = $prodi->id;
        $kaProdiBahasaInggris->save();
        
    }
}
