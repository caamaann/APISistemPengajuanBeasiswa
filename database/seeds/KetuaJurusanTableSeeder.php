<?php

use Illuminate\Database\Seeder;
use App\KetuaJurusan;
use App\User;
use App\Role;

class KetuaJurusanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {           
        $listItem = [
            ['nama' => 'Hendry, Dipl.Ing.HTL., MT.', 'nip' => '196306061995121001'],
            ['nama' => 'Dr. Syarif Hidayat, Dipl. Ing., MT', 'nip' => '196309031991021001'],
            ['nama' => 'Dr. Apip Badarudin, ST., MT.', 'nip' => '196612301995121001'],
            ['nama' => 'Dr.,Drs. Hartono Budi Santoso, MT', 'nip' => '196611071995121002'],
            ['nama' => 'R. Wahyu Trihartono, DU.Tech.,SST., M.T.', 'nip' => '196208291996011001'],
            ['nama' => 'Dr., Shoerya Shoelarta, LRSC., M.T.', 'nip' => '196607231993031002'],
            ['nama' => 'Bambang Wisnuadhi, S.Si., MT.', 'nip' => '197201061999031002'],
            ['nama' => 'Dr. Iwan Setiawan, SE., ME', 'nip' => '196703281993031001'],
            ['nama' => 'Sri Raharso, S.Sos., M.Si.', 'nip' => '196712042001121002'],
            ['nama' => 'Drs. SaudinM.Pd', 'nip' => '195904101989031001'],
        ];

        $i = 1;
        foreach($listItem as $item){
            $user = new User;
            $user->username = $item['nip'];
            $user->password = app('hash')->make($item['nip']);
            $user->save();
            $role = Role::where('name', 'ketuaJurusan')->firstOrFail();
            $user->roles()->attach($role->id);
            $item['user_id'] = $user->id;
            $item['jurusan_id'] = $i;
            KetuaJurusan::create($item);
            $i++;
        }
    }
}
