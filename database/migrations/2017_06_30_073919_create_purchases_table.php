<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id');
            $table->integer('store_id');
            $table->string('name');

            $table->decimal('price_paid')->nullable();
            $table->decimal('price_sold')->nullable();

            $table->date('date_purchased')->nullable();
            $table->text('notes')->nullable();

            $table->integer('tier_purchased')->default(1);
            $table->integer('bundle_id')->nullable();
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
        Schema::dropIfExists('purchases');
    }
}
