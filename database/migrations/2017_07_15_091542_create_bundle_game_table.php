<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBundleGameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bundle_game', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('game_id');
            $table->integer('bundle_id')->unsigned();
            $table->integer('tier');
            $table->timestamps();
        });

        Schema::table('bundle_game', function (Blueprint $table) {
            $table->foreign('bundle_id')->references('id')->on('bundles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bundle_game');
    }
}
