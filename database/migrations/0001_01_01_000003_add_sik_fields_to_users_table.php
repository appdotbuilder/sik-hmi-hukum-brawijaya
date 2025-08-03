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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('level', ['administrator', 'pengurus', 'kader'])->default('kader')->after('password');
            $table->string('nik')->nullable()->unique()->after('level')->comment('NIK diberikan oleh administrator');
            $table->string('profile_photo')->nullable()->after('nik');
            $table->string('komisariat')->default('Hukum Brawijaya')->after('profile_photo');
            $table->string('jurusan')->default('Hukum')->after('komisariat');
            $table->string('pt')->default('Universitas Brawijaya')->after('jurusan');
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O'])->nullable()->after('pt');
            $table->string('no_whatsapp')->nullable()->after('golongan_darah');
            $table->text('alamat_malang')->nullable()->after('no_whatsapp');
            $table->boolean('is_verified')->default(false)->after('alamat_malang')->comment('Verifikasi oleh administrator');
            $table->boolean('profile_completed')->default(false)->after('is_verified')->comment('Profile lengkap untuk akses penuh');
            
            // Add indexes for performance
            $table->index('level');
            $table->index('is_verified');
            $table->index('profile_completed');
            $table->index(['level', 'is_verified']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['users_level_index']);
            $table->dropIndex(['users_is_verified_index']);
            $table->dropIndex(['users_profile_completed_index']);
            $table->dropIndex(['users_level_is_verified_index']);
            
            $table->dropColumn([
                'level',
                'nik',
                'profile_photo',
                'komisariat',
                'jurusan',
                'pt',
                'golongan_darah',
                'no_whatsapp',
                'alamat_malang',
                'is_verified',
                'profile_completed'
            ]);
        });
    }
};