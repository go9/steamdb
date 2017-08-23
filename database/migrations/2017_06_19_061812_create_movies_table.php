<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('game_id')->unsigned();
          $table->integer('movie_id');
          $table->string("name");
          $table->text('thumbnail');
          $table->text('webm_sd');
          $table->text('webm_hd');
          $table->string("highlight");
          $table->timestamps();
        });

        Schema::table('movies', function(Blueprint $table){
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
        Schema::dropIfExists('movies');
    }
}
