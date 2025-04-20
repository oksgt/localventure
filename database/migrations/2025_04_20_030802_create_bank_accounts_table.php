<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name', 255);
            $table->string('account_name', 255);
            $table->string('account_number', 50)->unique();
            $table->boolean('account_status')->default(true);
            $table->boolean('is_public')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bank_accounts');
    }

};
