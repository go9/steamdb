<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateG2asTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('g2as', function (Blueprint $table) {
            $table->increments('id');
            $table->string("name");
            $table->text("slug");
            $table->text("thumbnail")->nullable();
            $table->text("smallImage")->nullable();
            $table->text("bigSearchImage")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('g2as');
    }
}
