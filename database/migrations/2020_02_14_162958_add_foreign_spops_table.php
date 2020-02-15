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
            $table->unsignedBigInteger('spop_id')->after("desa_id");

            $table->foreign('spop_id')->references("id")->on("spops");
        });

        Schema::table('data_subjek_pajaks', function (Blueprint $table) {
            $table->unsignedBigInteger('spop_id')->after("desa_id");

            $table->foreign('spop_id')->references("id")->on("spops");
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
