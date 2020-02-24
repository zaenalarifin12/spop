<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRujukansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rujukans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid')->unique()->nullable();
            $table->string('tahun');
            $table->string('nop');
            $table->string('nama_subjek_pajak');
            $table->string('alamat_wp');
            $table->string('alamat_op');
            $table->string('luas_bumi_sppt');
            $table->string('luas_bng_sppt');
            $table->string('njop_bumi_sppt');
            $table->string('njop_bng_sppt');
            $table->string('pbb');
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
        Schema::dropIfExists('rujukans');
    }
}
