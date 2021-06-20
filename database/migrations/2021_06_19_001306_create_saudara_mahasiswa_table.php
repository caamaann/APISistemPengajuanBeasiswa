<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSaudaraMahasiswaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('saudara_mahasiswa', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('mahasiswa_id')->unsigned()->index('saudara_mahasiswa_mahasiswa_id_foreign');
			$table->string('nama', 50);
			$table->integer('usia')->unsigned();
			$table->enum('status_pernikahan', array('Belum menikah','Menikah'));
			$table->enum('status_saudara', array('Adik','Kakak'));
			$table->enum('status_pekerjaan', array('Belum bekerja','Bekerja'));
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('saudara_mahasiswa');
	}

}
