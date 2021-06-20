<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMahasiswaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mahasiswa', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('user_id')->unsigned()->index('mahasiswa_user_id_foreign');
			$table->bigInteger('wali_kelas_id')->unsigned()->index('mahasiswa_wali_kelas_id_foreign');
			$table->bigInteger('program_studi_id')->unsigned()->index('mahasiswa_program_studi_id_foreign');
			$table->string('nim', 10)->unique();
			$table->string('nama', 50);
			$table->string('tempat_lahir', 30)->nullable();
			$table->date('tanggal_lahir')->nullable();
			$table->enum('gender', array('l','p'))->nullable();
			$table->text('alamat', 65535)->nullable();
			$table->string('kota', 50)->nullable();
			$table->string('kode_pos', 10)->nullable();
			$table->string('nomor_hp', 13)->nullable();
			$table->string('email', 50)->unique();
			$table->string('nama_bank', 30)->nullable();
			$table->string('nomor_rekening', 30)->nullable();
			$table->float('ipk');
			$table->integer('semester')->unsigned();
			$table->integer('angkatan')->unsigned();
			$table->enum('status_keaktifan', array('Aktif','Tidak Aktif'))->default('Aktif');
			$table->string('file_transkrip_nilai')->nullable();
			$table->string('file_kk')->nullable();
			$table->string('file_ktm')->nullable();
			$table->string('sertifikat_ppkk')->nullable();
			$table->string('sertifikat_bn')->nullable();
			$table->string('sertifikat_metagama')->nullable();
			$table->string('sertifikat_butterfly')->nullable();
			$table->string('sertifikat_esq')->nullable();
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
		Schema::drop('mahasiswa');
	}

}
