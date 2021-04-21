<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beasiswa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama', 40);
            $table->text('deskripsi');
            $table->integer('biaya_pendidikan_per_semester');
            $table->integer('penghasilan_orang_tua_maksimal');
            $table->float('ipk_minimal');
            $table->date('awal_pendaftaran');
            $table->date('akhir_pendaftaran');
            $table->date('awal_penerimaan');
            $table->date('akhir_penerimaan');
            $table->unsignedInteger('bobot_ipk')->default(50);
            $table->unsignedInteger('bobot_prestasi')->default(30);
            $table->unsignedInteger('bobot_perilaku')->default(5);
            $table->unsignedInteger('bobot_organisasi')->default(5);
            $table->unsignedInteger('bobot_kemampuan_ekonomi')->default(10);
            $table->enum('status_pendaftaran', ['Dibuka', 'Ditutup'])->default('Dibuka');
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
        Schema::dropIfExists('beasiswa');
    }
}
