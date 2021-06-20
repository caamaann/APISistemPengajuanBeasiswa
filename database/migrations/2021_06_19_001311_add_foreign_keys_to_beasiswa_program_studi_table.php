<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToBeasiswaProgramStudiTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('beasiswa_program_studi', function(Blueprint $table)
		{
			$table->foreign('beasiswa_id')->references('id')->on('beasiswa')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('program_studi_id')->references('id')->on('program_studi')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('beasiswa_program_studi', function(Blueprint $table)
		{
			$table->dropForeign('beasiswa_program_studi_beasiswa_id_foreign');
			$table->dropForeign('beasiswa_program_studi_program_studi_id_foreign');
		});
	}

}
