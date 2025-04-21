<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ticket_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained('destinations')->onDelete('cascade');
            $table->enum('visitor_type', ['individual', 'group']);
            $table->date('visit_date');
            $table->string('visitor_name', 255);
            $table->text('visitor_address');
            $table->string('visitor_phone', 20);
            $table->text('visitor_origin_description')->nullable();
            $table->string('visitor_email', 255)->nullable();
            $table->integer('visitor_age')->nullable();
            $table->enum('visitor_gender', ['M', 'F']);
            $table->integer('total_visitor');
            $table->decimal('total_price', 10, 2);
            $table->string('billing_number', 50)->unique();
            $table->enum('payment_status', ['pending', 'paid'])->default('pending');
            $table->enum('purchasing_type', ['onsite', 'online']);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket_orders');
    }

};
