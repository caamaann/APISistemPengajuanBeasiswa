<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKetuaProgramStudiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ketua_program_studi', function (Blueprint $table) {
            $table->string('id');
            $table->string('user_id');
            $table->string('program_studi_id')->unique();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('program_studi_id')->references('id')->on('program_studi');
            $table->string('nip', 20)->unique();
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
        Schema::dropIfExists('ketua_program_studi');
    }
}
