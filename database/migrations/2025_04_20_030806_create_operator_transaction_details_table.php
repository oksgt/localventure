<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('operator_transaction_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_transaction_id')->constrained('operator_transaction')->onDelete('cascade');
            $table->foreignId('ticket_order_id')->constrained('ticket_orders')->onDelete('cascade');
            $table->integer('qty');
            $table->decimal('amount', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('operator_transaction_detail');
    }

};
