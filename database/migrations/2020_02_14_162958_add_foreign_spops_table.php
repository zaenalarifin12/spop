<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignSpopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_letak_objeks', function (Blueprint $table) {
            $table->unsignedBigInteger('spop_id')->after("desa_id")->nullable();

            $table->foreign('spop_id')->references("id")->on("spops")->onDelete("set null");
        });
        
        Schema::table('data_subjek_pajaks', function (Blueprint $table) {
            $table->unsignedBigInteger('spop_id')->after("kabupaten")->nullable();

            $table->foreign('spop_id')->references("id")->on("spops")->onDelete("set null");
        });

        Schema::table('data_tanahs', function (Blueprint $table) {
            $table->unsignedBigInteger('spop_id')->after("jenis_tanah_id")->nullable();

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
        //
    }
}
