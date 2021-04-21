<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSertifikatPrestasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sertifikat_prestasi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->foreign('mahasiswa_id')->references('id')->on('mahasiswa');
            $table->string('file_sertifikat');
            $table->enum('tingkat_prestasi',['Internasional', 'Nasional', 'Provinsi', 'Kota']);
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
        Schema::dropIfExists('sertifikat_prestasi');
    }
}
