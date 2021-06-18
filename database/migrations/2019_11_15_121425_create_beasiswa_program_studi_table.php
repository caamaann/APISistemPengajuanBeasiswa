<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeasiswaProgramStudiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beasiswa_program_studi', function (Blueprint $table) {
            $table->string('beasiswa_id');
            $table->string('program_studi_id');
            $table->foreign('beasiswa_id')->references('id')->on('beasiswa');
            $table->foreign('program_studi_id')->references('id')->on('program_studi');
            $table->integer('angkatan');
            $table->integer('kuota');
            $table->primary(['beasiswa_id','program_studi_id','angkatan'], 'beasiswa_program_studi_bsw_prodi_angkatan_primary');
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
        Schema::dropIfExists('beasiswa_program_studi');
    }
}
