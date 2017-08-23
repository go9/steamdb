<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('thumbnail')->nullable();
            $table->text('url')->nullable();
        });

        // Default Values
        DB::table('stores')->insert(
            [
                [
                    'name' => 'SteamGM',
                    'id' => 100
                ],
                [
                    'name' => 'G2A',
                    'id' => 101
                ]
            ]
        );
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
