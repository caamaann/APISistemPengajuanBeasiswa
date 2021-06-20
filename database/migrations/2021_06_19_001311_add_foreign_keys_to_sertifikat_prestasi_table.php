<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSertifikatPrestasiTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('sertifikat_prestasi', function(Blueprint $table)
		{
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
		Schema::table('sertifikat_prestasi', function(Blueprint $table)
		{
			$table->dropForeign('sertifikat_prestasi_mahasiswa_id_foreign');
		});
	}

}
