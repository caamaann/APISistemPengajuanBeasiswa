<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMahasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('program_studi_id');            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('program_studi_id')->references('id')->on('program_studi');                        
            $table->string('nim', 10)->unique();
            $table->string('nama', 50);
            $table->string('tempat_lahir', 30)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('gender', ['l', 'p'])->nullable();
            $table->text('alamat')->nullable();
            $table->string('kota', 50)->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->string('nomor_hp', 13)->nullable();
            $table->string('email', 50)->unique();
            $table->string('nama_bank', 30)->nullable();
            $table->string('nomor_rekening', 30)->nullable();
            $table->float('ipk');
            $table->unsignedInteger('semester');
            $table->unsignedInteger('angkatan');
            $table->enum('status_keaktifan', ['Aktif', 'Tidak Aktif'])->default('Aktif');
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
        Schema::dropIfExists('mahasiswa');
    }
}
