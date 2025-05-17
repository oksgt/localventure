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
            $table->string('visitor_name', 255)->nullable();
            $table->text('visitor_address')->nullable();
            $table->string('visitor_phone', 20)->nullable();
            $table->text('visitor_origin_description')->nullable();
            $table->string('visitor_email', 255)->nullable();
            $table->integer('visitor_age')->nullable();

            $table->integer('id_kecamatan')->nullable();
            $table->integer('id_kabupaten')->nullable();
            $table->integer('id_provinsi')->nullable();

            $table->integer('visitor_male_count')->nullable()->default(0);
            $table->integer('visitor_female_count')->nullable()->default(0);

            $table->integer('total_visitor');
            $table->decimal('total_price', 10, 2);
            $table->string('billing_number', 50)->unique();
            $table->enum('payment_status', ['pending', 'paid', 'rejected'])->default('pending');
            $table->enum('purchasing_type', ['onsite', 'online']);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

            $table->integer('payment_type_id')->nullable()->after('column_name_here');
            $table->integer('bank_id')->nullable()->after('payment_type_id');

            $table->index('visit_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket_orders');
    }

};
