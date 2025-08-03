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
        Schema::create('book_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'borrowed', 'returned', 'rejected'])->default('pending')->comment('Status peminjaman');
            $table->datetime('borrowed_at')->nullable()->comment('Tanggal mulai pinjam');
            $table->datetime('due_date')->nullable()->comment('Tanggal jatuh tempo');
            $table->datetime('returned_at')->nullable()->comment('Tanggal dikembalikan');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('verified_at')->nullable()->comment('Tanggal verifikasi');
            $table->text('notes')->nullable()->comment('Catatan tambahan');
            $table->timestamps();
            
            // Add indexes for performance
            $table->index('user_id');
            $table->index('book_id');
            $table->index('status');
            $table->index('borrowed_at');
            $table->index('due_date');
            $table->index('returned_at');
            $table->index(['status', 'user_id']);
            $table->index(['status', 'due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_loans');
    }
};