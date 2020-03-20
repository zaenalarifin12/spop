<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataLetakObjeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_letak_objeks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('nama_jalan');
            $table->string('blok_kav');
            $table->string('rw', 3);
            $table->string('rt', 2);
            $table->unsignedBigInteger('desa_id')->nullable();
            $table->unsignedBigInteger('spop_id')->nullable();
            $table->timestamps();

            $table->foreign('desa_id')->references("id")->on("desas")->onDelete("set null");
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
        Schema::dropIfExists('data_letak_objeks');
    }
}
