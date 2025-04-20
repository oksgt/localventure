<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('destination_gallery', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained('destinations')->onDelete('cascade');
            $table->string('original_file_name', 255);
            $table->string('filename', 255);
            $table->string('file_ext', 10);
            $table->boolean('is_cover')->default(false);
            $table->integer('file_size')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('destination_gallery');
    }

};
