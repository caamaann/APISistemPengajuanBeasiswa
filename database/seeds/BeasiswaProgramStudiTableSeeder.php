<?php

use Illuminate\Database\Seeder;
use App\Beasiswa;
use App\ProgramStudi;

class BeasiswaProgramStudiTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$beasiswa = Beasiswa::findOrFail(1);
        $programStudi = ProgramStudi::findOrFail(23);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2017, 'kuota' => 2]);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2018, 'kuota' => 2]);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2019, 'kuota' => 2]);
        $programStudi = ProgramStudi::findOrFail(24);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2017, 'kuota' => 2]);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2018, 'kuota' => 2]);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2019, 'kuota' => 2]);
        $programStudi = ProgramStudi::findOrFail(25);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2017, 'kuota' => 2]);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2018, 'kuota' => 2]);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2019, 'kuota' => 2]);
        $programStudi = ProgramStudi::findOrFail(27);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2017, 'kuota' => 2]);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2018, 'kuota' => 2]);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2019, 'kuota' => 2]);

        $beasiswa = Beasiswa::findOrFail(2);
        $programStudi = ProgramStudi::findOrFail(23);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2017, 'kuota' => 2]);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2018, 'kuota' => 2]);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2019, 'kuota' => 2]);
        $programStudi = ProgramStudi::findOrFail(24);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2017, 'kuota' => 2]);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2018, 'kuota' => 2]);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2019, 'kuota' => 2]);
        $programStudi = ProgramStudi::findOrFail(25);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2017, 'kuota' => 2]);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2018, 'kuota' => 2]);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2019, 'kuota' => 2]);
        $programStudi = ProgramStudi::findOrFail(27);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2017, 'kuota' => 2]);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2018, 'kuota' => 2]);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2019, 'kuota' => 2]);

        $beasiswa = Beasiswa::findOrFail(3);
        $programStudi = ProgramStudi::findOrFail(23);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2017, 'kuota' => 2]);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2018, 'kuota' => 2]);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2019, 'kuota' => 2]);
        $programStudi = ProgramStudi::findOrFail(24);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2017, 'kuota' => 2]);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2018, 'kuota' => 2]);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2019, 'kuota' => 2]);
        $programStudi = ProgramStudi::findOrFail(25);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2017, 'kuota' => 2]);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2018, 'kuota' => 2]);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2019, 'kuota' => 2]);
        $programStudi = ProgramStudi::findOrFail(27);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2017, 'kuota' => 2]);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2018, 'kuota' => 2]);
        $beasiswa->programStudi()->attach($programStudi->id, ['angkatan'=> 2019, 'kuota' => 2]);
    }
}
