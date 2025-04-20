<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->text('address');
            $table->string('phone_number_1', 20)->nullable();
            $table->string('phone_number_2', 20)->nullable();
            $table->string('mobile_phone_number_1', 20)->nullable();
            $table->string('mobile_phone_number_2', 20)->nullable();
            $table->string('email', 255)->unique();
            $table->string('website', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('institutions');
    }

};
