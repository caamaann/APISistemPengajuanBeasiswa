<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePendaftarBeasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pendaftar_beasiswa', function (Blueprint $table) {
            $table->string('mahasiswa_id');
            $table->string('beasiswa_id');
            $table->foreign('mahasiswa_id')->references('id')->on('mahasiswa');
            $table->foreign('beasiswa_id')->references('id')->on('beasiswa');
            $table->integer('skor_ipk')->default(0);
            $table->integer('skor_prestasi')->default(0);
            $table->integer('skor_perilaku')->default(0);
            $table->integer('skor_organisasi')->default(0);
            $table->integer('skor_kemampuan_ekonomi')->default(0);
            $table->float('skor_akhir')->default(0);
            $table->enum('status', ['Mendaftar','Dinilai oleh wali kelas', 'Lulus seleksi program studi', 'Lulus seleksi jurusan', 'Menerima beasiswa'])->default('Mendaftar');
            $table->timestamps();
            $table->primary(['beasiswa_id', 'mahasiswa_id'], 'pendaftar_beasiswa_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pendaftar_beasiswa');
    }
}
