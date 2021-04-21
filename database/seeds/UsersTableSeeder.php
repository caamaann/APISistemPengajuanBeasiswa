<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $users = [
        //     ['username' => '171511019', 'password' => app('hash')->make('171511019')],
        //     ['username' => '171511060', 'password' => app('hash')->make('171511060')],            
        //     ['username' => '172416019', 'password' => app('hash')->make('172416019')],            
        //     ['username' => 'wali kelas A', 'password' => app('hash')->make('wali kelas A')],
        //     ['username' => 'wali kelas B', 'password' => app('hash')->make('wali kelas B')],
        //     ['username' => 'wali kelas prodi B', 'password' => app('hash')->make('wali kelas prodi B')],
        //     ['username' => 'ketua prodi A', 'password' => app('hash')->make('ketua prodi A')],
        //     ['username' => 'ketua prodi B', 'password' => app('hash')->make('ketua prodi B')],
        //     ['username' => 'ketua jurusan A', 'password' => app('hash')->make('ketua jurusan A')],
        //     ['username' => 'ketua jurusan B', 'password' => app('hash')->make('ketua jurusan B')],
        //     ['username' => 'pd3', 'password' => app('hash')->make('pd3')],
        //     ['username' => 'admin', 'password' => app('hash')->make('admin')],
        // ];

        $users = [
            // ['username' => '171511019', 'password' => app('hash')->make('171511019')],
            // ['username' => '171511060', 'password' => app('hash')->make('171511060')],            
            // ['username' => '172416019', 'password' => app('hash')->make('172416019')],            
            // ['password' => app('hash')->make('196312131992012001'), 'username' => '196312131992012001'],
            // ['password' => app('hash')->make('196210211993031002'), 'username' => '196210211993031002'],
            // ['password' => app('hash')->make('199900000000000002'), 'username' => '199900000000000002'],
            // ['password' => app('hash')->make('198604122014041001'), 'username' => '198604122014041001'],
            // ['password' => app('hash')->make('197109031999032001'), 'username' => '197109031999032001'],
            // ['password' => app('hash')->make('197201061999031002'), 'username' => '197201061999031002'],
            // ['password' => app('hash')->make('196703281993031001'), 'username' => '196703281993031001'],
            // ['password' => app('hash')->make('199900000000000007'), 'username' => '199900000000000007'],
            ['username' => 'admin', 'password' => app('hash')->make('admin')],
            // ['username' => '196810141993032002', 'password' => app('hash')->make('196810141993032002')], // Bu Ani
            // ['username' => '171524019', 'password' => app('hash')->make('171524019')], // Gibran
        ];
        foreach($users as $user){
    		User::create($user);
		}
    }
}
