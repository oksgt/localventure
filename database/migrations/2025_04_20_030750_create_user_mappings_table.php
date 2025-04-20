<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_mapping', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Assuming `users` table exists
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade'); // Assuming `roles` table exists
            $table->foreignId('destination_id')->constrained('destinations')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_mapping');
    }

};
