<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSertifikatPrestasiTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sertifikat_prestasi', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('mahasiswa_id')->unsigned()->index('sertifikat_prestasi_mahasiswa_id_foreign');
			$table->string('file_sertifikat');
			$table->enum('tingkat_prestasi', array('Internasional','Nasional','Provinsi','Kota'));
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
		Schema::drop('sertifikat_prestasi');
	}

}
