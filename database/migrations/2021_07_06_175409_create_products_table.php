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
            $table->text('slogan')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();

            // Time of year the product is offered - for calendar pagination
            $table->datetime('starts_at');
            $table->datetime('ends_at');

            // Daily opening hours - for calendar columns
            $table->time('opens_at');
            $table->time('closes_at');

            $table->unsignedInteger('min_occupancy')->nullable();

            // Prices after VAT
            $table->unsignedInteger('unit_price');
            $table->unsignedFloat('vat');
            $table->boolean('is_flat')->default(false);

            $table->unsignedInteger('unit_price_flat')->nullable();
            $table->unsignedFloat('vat_flat')->nullable();
            // TODO: Text-flat?

            $table->unsignedFloat('deposit');

            // TODO: What if a product can be hosted in multiple rooms
            //       a) show ONE calendar for all rooms combined
            //          So in the end, one "room" can actually be multiple rooms??
            //       b) show multiple calendars, one per room
            //          -> move room_id to a many to many relation?
            //       I will build for option a)
            // DO b!!!!
            $table->foreignId('room_id')->constrained();
            $table->timestamps();
            $table->softDeletes();
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
