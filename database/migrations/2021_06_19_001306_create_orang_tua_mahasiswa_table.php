<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrangTuaMahasiswaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orang_tua_mahasiswa', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('mahasiswa_id')->unsigned()->index('orang_tua_mahasiswa_mahasiswa_id_foreign');
			$table->string('nama_ayah', 50);
			$table->string('tempat_lahir_ayah', 30);
			$table->date('tanggal_lahir_ayah');
			$table->text('alamat_ayah', 65535);
			$table->string('nomor_hp_ayah', 13);
			$table->string('pekerjaan_ayah', 50)->default('Tidak memiliki pekerjaan');
			$table->integer('penghasilan_ayah')->unsigned()->default(0);
			$table->string('file_keterangan_penghasilan_ayah')->nullable();
			$table->string('pekerjaan_sambilan_ayah', 50)->default('Tidak memiliki pekerjaan');
			$table->integer('penghasilan_sambilan_ayah')->unsigned()->default(0);
			$table->string('nama_ibu', 50);
			$table->string('tempat_lahir_ibu', 30);
			$table->date('tanggal_lahir_ibu');
			$table->text('alamat_ibu', 65535);
			$table->string('nomor_hp_ibu', 13);
			$table->string('pekerjaan_ibu', 50)->default('Tidak memiliki pekerjaan');
			$table->integer('penghasilan_ibu')->unsigned()->default(0);
			$table->string('file_keterangan_penghasilan_ibu')->nullable();
			$table->string('pekerjaan_sambilan_ibu', 50)->default('Tidak memiliki pekerjaan');
			$table->integer('penghasilan_sambilan_ibu')->unsigned()->default(0);
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('orang_tua_mahasiswa');
	}

}
