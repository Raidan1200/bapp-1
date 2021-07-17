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

            // This is the booking data we absolutely need
            $table->datetime('starts_at');
            $table->datetime('ends_at');
            $table->unsignedInteger('quantity');

            // These are editable copies of the product snapshot data
            $table->string('product_name');
            $table->unsignedInteger('unit_price');
            $table->unsignedFloat('vat');
            $table->boolean('flat')->default(false);

            // Keep a snapshot of the product at the time of booking
            // minus the slogan, description & image fields
            $table->json('product_snapshot');

            // TODO: should these really be constrained?
            //       how about nullable OR set_null on delete?
            $table->foreignId('room_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->foreignId('order_id')->constrained();
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
