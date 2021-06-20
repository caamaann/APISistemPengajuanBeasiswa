<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePendaftarBeasiswaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pendaftar_beasiswa', function(Blueprint $table)
		{
			$table->bigInteger('mahasiswa_id')->unsigned()->index('pendaftar_beasiswa_mahasiswa_id_foreign');
			$table->bigInteger('beasiswa_id')->unsigned();
			$table->integer('skor_ipk')->default(0);
			$table->integer('skor_prestasi')->default(0);
			$table->integer('skor_perilaku')->default(0);
			$table->integer('skor_organisasi')->default(0);
			$table->integer('skor_kemampuan_ekonomi')->default(0);
			$table->float('skor_akhir')->default(0.00);
			$table->enum('status', array('Mendaftar','Dinilai oleh wali kelas','Lulus seleksi program studi','Lulus seleksi jurusan','Menerima beasiswa'))->default('Mendaftar');
			$table->timestamps();
			$table->primary(['beasiswa_id','mahasiswa_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pendaftar_beasiswa');
	}

}
