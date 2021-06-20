<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSertifikatOrganisasiTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sertifikat_organisasi', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('mahasiswa_id')->unsigned()->index('sertifikat_organisasi_mahasiswa_id_foreign');
			$table->string('file_sertifikat');
			$table->enum('jenis', array('Pengurus Organisasi','Kepanitiaan Program Kerja Kemahasiswaan'));
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
		Schema::drop('sertifikat_organisasi');
	}

}
