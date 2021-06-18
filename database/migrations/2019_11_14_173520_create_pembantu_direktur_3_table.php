<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePembantuDirektur3Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembantu_direktur_3', function (Blueprint $table) {
            $table->string('id');
            $table->string('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('pembantu_direktur_3');
    }
}
