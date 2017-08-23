<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeveloperGameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('developer_game', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('game_id')->unsigned();
        $table->integer('developer_id');
        $table->timestamps();
      });

        Schema::table('developer_game', function (Blueprint $table) {
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('developer_game');
    }
}
