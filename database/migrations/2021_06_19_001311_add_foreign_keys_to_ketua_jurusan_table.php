<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToKetuaJurusanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ketua_jurusan', function(Blueprint $table)
		{
			$table->foreign('jurusan_id')->references('id')->on('jurusan')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
		Schema::table('ketua_jurusan', function(Blueprint $table)
		{
			$table->dropForeign('ketua_jurusan_jurusan_id_foreign');
			$table->dropForeign('ketua_jurusan_user_id_foreign');
		});
	}

}
