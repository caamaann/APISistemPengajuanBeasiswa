<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSertifikatOrganisasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sertifikat_organisasi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->foreign('mahasiswa_id')->references('id')->on('mahasiswa');
            $table->string('file_sertifikat');
            $table->enum('jenis',['Pengurus Organisasi', 'Kepanitiaan Program Kerja Kemahasiswaan']);
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
        Schema::dropIfExists('sertifikat_organisasi');
    }
}
