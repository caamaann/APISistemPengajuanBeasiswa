<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSaudaraMahasiswaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('saudara_mahasiswa', function(Blueprint $table)
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
		Schema::table('saudara_mahasiswa', function(Blueprint $table)
		{
			$table->dropForeign('saudara_mahasiswa_mahasiswa_id_foreign');
		});
	}

}
