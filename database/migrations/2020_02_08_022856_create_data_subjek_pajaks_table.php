<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataSubjekPajaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_subjek_pajaks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_jalan');
            $table->string('nama_subjek_pajak');
            $table->string('rt', 3);
            $table->string('rw', 2);
            $table->string('nomor_ktp', 16);
            $table->string('nomor_hp', 100)->nullable();
            $table->string('desa');
            $table->string('kabupaten');
            $table->unsignedBigInteger('status_id')->nullable();
            $table->unsignedBigInteger('pekerjaan_id')->nullable();
            $table->unsignedBigInteger('spop_id')->nullable();
            $table->timestamps();

            $table->foreign('status_id')->references("id")->on("statuses")->onDelete("set null");
            $table->foreign('pekerjaan_id')->references("id")->on("pekerjaans")->onDelete("set null");
            $table->foreign('spop_id')->references("id")->on("spops")->onDelete("set null");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_subjek_pajaks');
    }
}
