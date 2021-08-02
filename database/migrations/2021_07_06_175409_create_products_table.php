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

            // Time of year the product is offered
            $table->datetime('starts_at');
            $table->datetime('ends_at');

            // Daily opening hours
            $table->time('opens_at');
            $table->time('closes_at');

            $table->unsignedInteger('min_occupancy')->default(0);

            // All prices are after VAT
            $table->unsignedInteger('price');
            $table->unsignedFloat('vat');
            $table->unsignedFloat('deposit');
            $table->unsignedFloat('is_flat')->default(false);

            // TODO: I don't like those extra flat-fields stuff
            // IMHO  flat and non-flat should be 2 different products
            //    OR price, vat, deposit, is_flat belong in a separate prices table
            $table->unsignedInteger('price_flat')->nullable();
            $table->unsignedFloat('vat_flat')->nullable();
            $table->unsignedFloat('deposit_flat')->nullable();;

            $table->foreignId('room_id')->nullable()->constrained()->nullOnDelete();
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
