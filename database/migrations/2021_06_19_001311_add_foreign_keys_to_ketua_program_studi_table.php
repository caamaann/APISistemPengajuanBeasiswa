<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToKetuaProgramStudiTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ketua_program_studi', function(Blueprint $table)
		{
			$table->foreign('program_studi_id')->references('id')->on('program_studi')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('ketua_program_studi', function(Blueprint $table)
		{
			$table->dropForeign('ketua_program_studi_program_studi_id_foreign');
			$table->dropForeign('ketua_program_studi_user_id_foreign');
		});
	}

}
