<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWaliKelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wali_kelas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('jurusan_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('jurusan_id')->references('id')->on('jurusan');
            $table->string('nip', 20)->unique();
            $table->string('nama', 40);
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
        Schema::dropIfExists('wali_kelas');
    }
}
