<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBeasiswaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('beasiswa', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('nama', 40);
			$table->text('deskripsi', 65535);
			$table->integer('biaya_pendidikan_per_semester');
			$table->integer('penghasilan_orang_tua_maksimal');
			$table->float('ipk_minimal');
			$table->date('awal_pendaftaran');
			$table->date('akhir_pendaftaran');
			$table->date('awal_penerimaan');
			$table->date('akhir_penerimaan');
			$table->integer('bobot_ipk')->unsigned()->default(50);
			$table->integer('bobot_prestasi')->unsigned()->default(30);
			$table->integer('bobot_perilaku')->unsigned()->default(5);
			$table->integer('bobot_organisasi')->unsigned()->default(5);
			$table->integer('bobot_kemampuan_ekonomi')->unsigned()->default(10);
			$table->enum('status_pendaftaran', array('Dibuka','Ditutup'))->default('Dibuka');
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
		Schema::drop('beasiswa');
	}

}
