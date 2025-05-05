<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->string('service_provider');
            $table->string('location');
            $table->foreignId('added_by')->constrained('users')->onDelete('cascade');
            $table->string('image')->nullable();
            $table->decimal('price', 8, 2);
            $table->integer('duration')->comment('Duration in minutes');
            $table->string('category');
            $table->decimal('rating', 2, 1)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('services');
    }
};
