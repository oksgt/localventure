<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ticket_order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('ticket_orders')->onDelete('cascade');
            $table->foreignId('guest_type_id')->constrained('guest_types')->onDelete('cascade');
            $table->enum('day_type', ['weekend', 'weekday']);
            $table->date('visit_date');
            $table->decimal('insurance_price', 10, 2)->nullable();
            $table->decimal('base_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->integer('qty');
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket_order_details');
    }

};
