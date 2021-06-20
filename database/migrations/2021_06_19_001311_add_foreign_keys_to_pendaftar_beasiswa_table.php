<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPendaftarBeasiswaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('pendaftar_beasiswa', function(Blueprint $table)
		{
			$table->foreign('beasiswa_id')->references('id')->on('beasiswa')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('mahasiswa_id')->references('id')->on('mahasiswa')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('pendaftar_beasiswa', function(Blueprint $table)
		{
			$table->dropForeign('pendaftar_beasiswa_beasiswa_id_foreign');
			$table->dropForeign('pendaftar_beasiswa_mahasiswa_id_foreign');
		});
	}

}
