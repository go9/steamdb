<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('type')->default("unknown");
            $table->boolean('public')->default(-1);

            // external skus
            $table->integer('steam_id');
            $table->integer('g2a_id')->nullable();

            // Descriptions
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->text('about_the_game')->nullable();

            // Platforms
            $table->boolean('platform_windows')->nullable();
            $table->boolean('platform_mac')->nullable();
            $table->boolean('platform_linux')->nullable();

            // System Req's
            $table->text('req_win_min')->nullable();
            $table->text('req_win_rec')->nullable();
            $table->text('req_mac_min')->nullable();
            $table->text('req_mac_rec')->nullable();
            $table->text('req_lin_min')->nullable();
            $table->text('req_lin_rec')->nullable();

            // Misc
            $table->text('reviews')->nullable();
            $table->text('website')->nullable();
            $table->text('legal_notice')->nullable();
            $table->string('release_date')->nullable();
            $table->boolean('is_free')->nullable();
            $table->integer('full_game')->nullable(); // For DLC
            $table->integer('required_age')->nullable();
            $table->text('support_url')->nullable();
            $table->string('support_email')->nullable();
            $table->string('external_account')->nullable();
            $table->string('controller_support')->nullable();

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
        Schema::dropIfExists('games');
    }
}
