<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sacrifice_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sacrifice_id')->constrained('sacrifices')->onDelete('cascade');
            $table->enum('photo_category', ['hewan', 'penyembelihan', 'pengemasan', 'distribusi']);
            $table->string('file_path');
            $table->string('file_name');
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->string('caption')->nullable();
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();

            // Composite index for category-filtered gallery queries
            $table->index(['sacrifice_id', 'photo_category'], 'idx_sacrifice_category');
            $table->index(['sacrifice_id', 'order'], 'idx_sacrifice_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sacrifice_galleries');
    }
};
