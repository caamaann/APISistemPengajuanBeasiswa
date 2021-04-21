<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramStudiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_studi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('jurusan_id');
            $table->foreign('jurusan_id')->references('id')->on('program_studi');
            $table->string('nama', 50);
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
        Schema::dropIfExists('program_studi');
    }
}
