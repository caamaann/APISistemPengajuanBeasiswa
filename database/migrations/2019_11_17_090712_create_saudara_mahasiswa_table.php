<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaudaraMahasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saudara_mahasiswa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->foreign('mahasiswa_id')->references('id')->on('mahasiswa');
            $table->string('nama', 50);
            $table->unsignedInteger('usia');
            $table->enum('status_pernikahan', ['Belum menikah', 'Menikah']);
            $table->enum('status_saudara', ['Adik', 'Kakak']);
            $table->enum('status_pekerjaan', ['Belum bekerja', 'Bekerja']);
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
        Schema::dropIfExists('saudara_mahasiswa');
    }
}
