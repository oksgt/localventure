<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('operator_transaction', function (Blueprint $table) {
            $table->id();
            $table->string('billing_number', 50)->unique();
            $table->integer('total_ticket_order');
            $table->decimal('total_amount', 10, 2);
            $table->string('transfer_receipt', 255)->nullable();
            $table->datetime('transfer_date')->nullable();
            $table->boolean('transfer_approval')->default(false);
            $table->datetime('transfer_approval_date')->nullable();
            $table->foreignId('transfer_approval_user')->nullable()->constrained('user_mapping')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('operator_transaction');
    }

};
