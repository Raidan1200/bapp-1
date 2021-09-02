<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->text('slogan')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();

            // Time of year the package is offered
            $table->datetime('starts_at');
            $table->datetime('ends_at');

            // Daily opening hours
            $table->time('opens_at');
            $table->time('closes_at');

            $table->unsignedInteger('min_occupancy')->default(0);

            // All prices are after VAT
            $table->unsignedInteger('unit_price');
            $table->unsignedFloat('vat');
            $table->unsignedFloat('deposit');
            $table->boolean('is_flat')->default(false);

            $table->unsignedInteger('price_flat')->nullable();
            $table->unsignedFloat('vat_flat')->nullable();
            $table->unsignedFloat('deposit_flat')->nullable();;

            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('packages');
    }
}
