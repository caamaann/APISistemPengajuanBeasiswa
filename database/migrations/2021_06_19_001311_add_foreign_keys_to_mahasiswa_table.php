<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToMahasiswaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('mahasiswa', function(Blueprint $table)
		{
			$table->foreign('program_studi_id')->references('id')->on('program_studi')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('wali_kelas_id')->references('id')->on('wali_kelas')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('mahasiswa', function(Blueprint $table)
		{
			$table->dropForeign('mahasiswa_program_studi_id_foreign');
			$table->dropForeign('mahasiswa_user_id_foreign');
			$table->dropForeign('mahasiswa_wali_kelas_id_foreign');
		});
	}

}
