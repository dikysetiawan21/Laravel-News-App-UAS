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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relasi ke tabel users (pengirim berita)
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // Relasi ke tabel categories
            $table->string('title');
            $table->string('slug')->unique(); // Slug untuk URL berita
            $table->string('image')->nullable(); // Nama file gambar
            $table->longText('content'); // Konten berita
            $table->enum('status', ['draft', 'published'])->default('draft'); // Status berita: draft atau published
            $table->timestamp('published_at')->nullable(); // Tanggal publish, akan diisi saat di-approve
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};