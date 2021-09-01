<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('email');
            $table->string('logo')->nullable();
            // $table->json('invoice_data')->nullable(); // TODO bad name!
            // TODO - be careful when changing reminder and delete fields
            //        because reminders/deletions might get skipped/repeated
            // TODO: Default Werte?
            $table->unsignedInteger('reminder_delay')->default(7);
            $table->unsignedInteger('check_delay')->default(7);
            $table->unsignedInteger('delete_delay')->default(10);
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
        Schema::dropIfExists('venues');
    }
}
