<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('payment_confirmation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_order_id');
            $table->string('billing_number');
            $table->decimal('transfer_amount', 15, 2);
            $table->string('bank_name');
            $table->string('account_name');
            $table->string('account_number');
            $table->string('image'); // Store image path
            $table->integer('status')->default(0);
            $table->softDeletes(); // Enables soft deletes
            $table->timestamps();

        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_confirmation');
    }
};
