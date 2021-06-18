<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrangTuaMahasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orang_tua_mahasiswa', function (Blueprint $table) {
            $table->string('id');
            $table->string('mahasiswa_id');
            $table->foreign('mahasiswa_id')->references('id')->on('mahasiswa');
            $table->string('nama_ayah', 50);
            $table->string('tempat_lahir_ayah', 30);
            $table->date('tanggal_lahir_ayah');
            $table->text('alamat_ayah');
            $table->string('nomor_hp_ayah', 13);
            $table->string('pekerjaan_ayah', 50)->default('Tidak memiliki pekerjaan');
            $table->unsignedInteger('penghasilan_ayah')->default(0);
            $table->string('file_keterangan_penghasilan_ayah')->nullable();
            $table->string('pekerjaan_sambilan_ayah', 50)->default('Tidak memiliki pekerjaan');
            $table->unsignedInteger('penghasilan_sambilan_ayah')->default(0);
            $table->string('nama_ibu', 50);
            $table->string('tempat_lahir_ibu', 30);
            $table->date('tanggal_lahir_ibu');
            $table->text('alamat_ibu');
            $table->string('nomor_hp_ibu', 13);
            $table->string('pekerjaan_ibu', 50)->default('Tidak memiliki pekerjaan');
            $table->unsignedInteger('penghasilan_ibu')->default(0);
            $table->string('file_keterangan_penghasilan_ibu')->nullable();
            $table->string('pekerjaan_sambilan_ibu', 50)->default('Tidak memiliki pekerjaan');
            $table->unsignedInteger('penghasilan_sambilan_ibu')->default(0);
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
        Schema::dropIfExists('orang_tua_mahasiswa');
    }
}
