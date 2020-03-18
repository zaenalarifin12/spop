<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRincianDataBangunansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rincian_data_bangunans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid')->unique();
            $table->integer('luas_bangunan');
            $table->string('tahun_dibangun', 4);
            $table->string('tahun_renovasi', 4);
            $table->integer('jumlah_lantai');
            $table->integer('daya_listrik');
            $table->unsignedBigInteger('jenis_penggunaan_bangunan_id')->nullable();
            $table->unsignedBigInteger('kondisi_id')->nullable();
            $table->unsignedBigInteger('konstruksi_id')->nullable();
            $table->unsignedBigInteger('atap_id')->nullable();
            $table->unsignedBigInteger('dinding_id')->nullable();
            $table->unsignedBigInteger('lantai_id')->nullable();
            $table->unsignedBigInteger('langit_id')->nullable();
            $table->unsignedBigInteger('spop_id')->nullable();
            $table->timestamps();

            $table->foreign('jenis_penggunaan_bangunan_id')
                    ->references("id")->on("jenis_penggunaan_bangunans")->onDelete("set null");
            $table->foreign('kondisi_id')
                    ->references("id")->on("kondisis")->onDelete("set null");
            $table->foreign('konstruksi_id')
                    ->references("id")->on("konstruksis")->onDelete("set null");
            $table->foreign('atap_id')
                    ->references("id")->on("ataps")->onDelete("set null");
            $table->foreign('dinding_id')
                    ->references("id")->on("dindings")->onDelete("set null");
            $table->foreign('lantai_id')
                    ->references("id")->on("lantais")->onDelete("set null");
            $table->foreign('langit_id')
                    ->references("id")->on("langits")->onDelete("set null");
            $table->foreign('spop_id')
                    ->references("id")->on("spops")->onDelete("set null");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rincian_data_bangunans');
    }
}
