<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIconsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('icons', function (Blueprint $table) {
            $table->string('id');
            $table->string('name')->nullable();
            $table->string('default_color')->nullable();
            $table->string('default_size')->nullable();
        });

        // Default Values
        DB::table('icons')->insert(
            [
                [
                    'id' => 'book',
                    "name" => "Library",
                    "default_color" => "blue",
                    "default_size" => ""
                ],
                [
                    'id' => 'archive',
                    "name" => "Inventory",
                    "default_color" => "brown",
                    "default_size" => ""
                ],
                [
                    'id' => 'extenral-link-square',
                    "name" => "Link",
                    "default_color" => "",
                    "default_size" => ""
                ],
                [
                    'id' => 'sticky-note',
                    "name" => "Note",
                    "default_color" => "yellow",
                    "default_size" => ""
                ],
                [
                    'id' => 'plus',
                    "name" => "Add",
                    "default_color" => "",
                    "default_size" => ""
                ],
                [
                    'id' => 'plus-circle',
                    "name" => "Bulk Add",
                    "default_color" => "",
                    "default_size" => ""
                ],
                [
                    'id' => 'trash',
                    "name" => "Delete",
                    "default_color" => "red",
                    "default_size" => ""
                ],
                [
                    'id' => 'arrow-down',
                    "name" => "Down",
                    "default_color" => "",
                    "default_size" => ""
                ],
                [
                    'id' => 'arrow-up',
                    "name" => "Up",
                    "default_color" => "",
                    "default_size" => ""
                ],
                [
                    'id' => 'circle',
                    "name" => "Circle",
                    "default_color" => "",
                    "default_size" => ""
                ],
                [
                    'id' => 'pencil-square-o',
                    "name" => "Edit",
                    "default_color" => "",
                    "default_size" => ""
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
        Schema::dropIfExists('icons');
    }
}
