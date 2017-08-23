<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamePurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_purchase', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('game_id');
            $table->integer('purchase_id')->unsigned();

            $table->integer('availability');

            $table->decimal('price_paid')->nullable();
            $table->decimal('price_sold')->nullable();

            $table->date('date_paid')->nullable();
            $table->date('date_sold')->nullable();

            $table->string('key')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::table('game_purchase', function (Blueprint $table) {
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_purchase');
    }
}
