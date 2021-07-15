<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('excerpt')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('capacity');
            $table->unsignedInteger('price');
            $table->unsignedInteger('deposit');
            $table->time('opens_at');
            $table->time('closes_at');
            $table->foreignId('venue_id')->constrained();
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
        Schema::dropIfExists('products');
    }
}
