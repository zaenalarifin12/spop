<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataTanahsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_tanahs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('luas_tanah');
            $table->unsignedbigInteger('jenis_tanah_id')->nullable();
            $table->unsignedBigInteger('spop_id')->nullable();
            $table->timestamps();

            $table->foreign('jenis_tanah_id')->references("id")->on("jenis_tanahs")->onDelete("set null");
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
        Schema::dropIfExists('data_tanahs');
    }
}
