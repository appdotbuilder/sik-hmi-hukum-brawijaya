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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Judul buku');
            $table->string('author')->comment('Pengarang buku');
            $table->enum('type', ['book', 'work'])->default('book')->comment('Tipe: buku atau karya');
            $table->boolean('is_available_print')->default(true)->comment('Tersedia dalam bentuk cetak');
            $table->boolean('is_available_digital')->default(false)->comment('Tersedia dalam bentuk digital');
            $table->string('digital_url')->nullable()->comment('URL akses digital');
            $table->text('description')->nullable()->comment('Deskripsi buku');
            $table->integer('loan_duration_days')->default(14)->comment('Durasi peminjaman dalam hari');
            $table->timestamps();
            
            // Add indexes for performance
            $table->index('title');
            $table->index('author');
            $table->index('type');
            $table->index('is_available_print');
            $table->index('is_available_digital');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};