<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pricing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained('destinations')->onDelete('cascade');
            $table->foreignId('guest_type_id')->constrained('guest_types')->onDelete('cascade');
            $table->enum('day_type', ['weekend', 'weekday']);
            $table->decimal('base_price', 10, 2);
            $table->string('currency', 10)->default('IDR');
            $table->decimal('insurance_price', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pricing');
    }

};
