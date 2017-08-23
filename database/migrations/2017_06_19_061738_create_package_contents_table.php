<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("content_id");
            $table->integer("package_id")->unsigned();
            $table->timestamps();
        });

        Schema::table('package_contents', function(Blueprint $table){
            $table->foreign('package_id')->references('id')->on('games')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_contents');
    }
}
