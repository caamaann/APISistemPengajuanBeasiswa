<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBeasiswaProgramStudiTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('beasiswa_program_studi', function(Blueprint $table)
		{
			$table->bigInteger('beasiswa_id')->unsigned();
			$table->bigInteger('program_studi_id')->unsigned()->index('beasiswa_program_studi_program_studi_id_foreign');
			$table->integer('angkatan');
			$table->integer('kuota');
			$table->timestamps();
			$table->primary(['beasiswa_id','program_studi_id','angkatan']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('beasiswa_program_studi');
	}

}
