<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->datetime('starts_at')->nullable();
            $table->datetime('ends_at')->nullable();
            $table->unsignedInteger('interval')->nullable();

            $table->string('package_name');
            $table->unsignedInteger('quantity');
            $table->integer('unit_price');
            $table->unsignedFloat('vat');
            $table->unsignedFloat('deposit');
            $table->boolean('is_flat');
            $table->json('config')->nullable();
            $table->json('snapshot')->nullable();

            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->nullable()->constrained()->nullOnDelete();
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
        Schema::dropIfExists('bookings');
    }
}
