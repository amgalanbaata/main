<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('number');
            $table->string('comment');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('address')->nullable();
            $table->integer('status')->nullable();
            $table->string('admin_comment')->nullable();
            $table->integer('type');
            $table->integer('category');
            $table->string('agreed')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
