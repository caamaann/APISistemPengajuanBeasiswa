<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKetuaJurusanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ketua_jurusan', function (Blueprint $table) {
            $table->string('id');
            $table->string('user_id');
            $table->string('jurusan_id')->unique();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('jurusan_id')->references('id')->on('jurusan');
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
        Schema::dropIfExists('ketua_jurusan');
    }
}
