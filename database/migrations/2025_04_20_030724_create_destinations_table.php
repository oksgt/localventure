<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->text('description');
            $table->string('address', 255)->nullable();
            $table->boolean('is_publish')->default(false);
            $table->boolean('available')->default(false);
            $table->point('latlon');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('destinations');
    }

};
