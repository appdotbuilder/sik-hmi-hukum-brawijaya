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
        Schema::create('works', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Judul karya');
            $table->enum('type', ['artikel', 'esai', 'kti'])->comment('Jenis karya: artikel/jurnal, esai, atau KTI');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_available_print')->default(false)->comment('Tersedia dalam bentuk cetak');
            $table->boolean('is_available_digital')->default(true)->comment('Tersedia dalam bentuk digital');
            $table->string('digital_url')->nullable()->comment('URL akses digital');
            $table->text('description')->nullable()->comment('Deskripsi karya');
            $table->timestamps();
            
            // Add indexes for performance
            $table->index('title');
            $table->index('type');
            $table->index('user_id');
            $table->index('is_available_print');
            $table->index('is_available_digital');
            $table->index(['type', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('works');
    }
};