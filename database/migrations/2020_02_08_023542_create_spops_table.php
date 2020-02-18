<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spops', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nop');
            $table->string('nop_asal')->nullable();
            $table->unsignedBigInteger('data_letak_objek_id')->nullable();
            $table->unsignedBigInteger('data_subjek_pajak_id')->nullable();
            $table->unsignedBigInteger('data_tanah_id')->nullable();
            
            $table->timestamps();

            $table->foreign('data_letak_objek_id')
                ->references("id")->on("data_letak_objeks")->onDelete("cascade");
            $table->foreign('data_subjek_pajak_id')
                ->references("id")->on("data_subjek_pajaks")->onDelete("cascade");
            $table->foreign('data_tanah_id')
                ->references("id")->on("data_tanahs")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spops');
    }
}
