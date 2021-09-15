<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id');
            $table->string('state');
            $table->boolean('cash_payment');
            $table->text('notes')->nullable();
            $table->foreignId('venue_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->constrained();
            $table->datetime('starts_at');
            $table->datetime('deposit_invoice_at')->nullable();
            $table->boolean('needs_check')->default(false);
            $table->datetime('deposit_paid_at')->nullable();
            $table->integer('deposit_amount')->nullable();
            $table->datetime('interim_invoice_at')->nullable();
            $table->datetime('interim_paid_at')->nullable();
            $table->integer('interim_amount')->nullable();
            $table->datetime('final_invoice_at')->nullable();
            $table->datetime('final_paid_at')->nullable();
            $table->datetime('cancelled_at')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
