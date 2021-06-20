<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToProgramStudiTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('program_studi', function(Blueprint $table)
		{
			$table->foreign('jurusan_id')->references('id')->on('program_studi')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('program_studi', function(Blueprint $table)
		{
			$table->dropForeign('program_studi_jurusan_id_foreign');
		});
	}

}
