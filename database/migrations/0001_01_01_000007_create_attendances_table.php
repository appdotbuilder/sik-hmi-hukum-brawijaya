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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['bidang', 'program_kegiatan'])->comment('Tipe absensi');
            $table->enum('bidang', ['pembinaan_anggota', 'litbang', 'p2k', 'pp', 'ptkp', 'kpp'])->nullable()->comment('Bidang absensi');
            $table->string('program_kegiatan')->nullable()->comment('Nama program kegiatan presidium pengurus');
            $table->string('title')->comment('Judul kegiatan/pertemuan');
            $table->text('description')->nullable()->comment('Deskripsi kegiatan');
            $table->datetime('date')->comment('Tanggal dan waktu kegiatan');
            $table->enum('status', ['present', 'absent', 'late'])->default('absent')->comment('Status kehadiran');
            $table->datetime('checked_in_at')->nullable()->comment('Waktu check-in');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            // Add indexes for performance
            $table->index('user_id');
            $table->index('type');
            $table->index('bidang');
            $table->index('program_kegiatan');
            $table->index('date');
            $table->index('status');
            $table->index('created_by');
            $table->index(['user_id', 'date']);
            $table->index(['type', 'bidang']);
            $table->index(['status', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};