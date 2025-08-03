<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Book;
use App\Models\BookLoan;
use App\Models\User;
use App\Models\Work;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SIKSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Administrator SIK',
            'email' => 'admin@hmihukumbrawijaya.org',
            'password' => Hash::make('password'),
            'level' => User::LEVEL_ADMINISTRATOR,
            'nik' => 'HMI-ADM-0001',
            'komisariat' => 'Hukum Brawijaya',
            'jurusan' => 'Hukum',
            'pt' => 'Universitas Brawijaya',
            'golongan_darah' => 'A',
            'no_whatsapp' => '081234567890',
            'alamat_malang' => 'Jl. MT. Haryono No. 169, Malang',
            'is_verified' => true,
            'profile_completed' => true,
            'email_verified_at' => now(),
        ]);

        // Create pengurus users
        $pengurus1 = User::create([
            'name' => 'Ahmad Kholil',
            'email' => 'kholil@hmihukumbrawijaya.org',
            'password' => Hash::make('password'),
            'level' => User::LEVEL_PENGURUS,
            'nik' => 'HMI-PGR-0001',
            'komisariat' => 'Hukum Brawijaya',
            'jurusan' => 'Hukum',
            'pt' => 'Universitas Brawijaya',
            'golongan_darah' => 'B',
            'no_whatsapp' => '081234567891',
            'alamat_malang' => 'Jl. Veteran No. 12, Malang',
            'is_verified' => true,
            'profile_completed' => true,
            'email_verified_at' => now(),
        ]);

        $pengurus2 = User::create([
            'name' => 'Siti Aminah',
            'email' => 'aminah@hmihukumbrawijaya.org',
            'password' => Hash::make('password'),
            'level' => User::LEVEL_PENGURUS,
            'nik' => 'HMI-PGR-0002',
            'komisariat' => 'Hukum Brawijaya',
            'jurusan' => 'Hukum',
            'pt' => 'Universitas Brawijaya',
            'golongan_darah' => 'O',
            'no_whatsapp' => '081234567892',
            'alamat_malang' => 'Jl. Ijen No. 25, Malang',
            'is_verified' => true,
            'profile_completed' => true,
            'email_verified_at' => now(),
        ]);

        // Create verified kader users
        User::factory(15)->create([
            'level' => User::LEVEL_KADER,
            'is_verified' => true,
            'profile_completed' => true,
            'email_verified_at' => now(),
        ]);

        // Create unverified kader users
        User::factory(5)->create([
            'level' => User::LEVEL_KADER,
            'is_verified' => false,
            'profile_completed' => false,
            'nik' => null,
            'email_verified_at' => now(),
        ]);

        // Create books
        Book::factory(20)->create();
        
        // Create some specific featured books
        Book::create([
            'title' => 'Sejarah Pergerakan HMI',
            'author' => 'Prof. Dr. Lafran Pane',
            'type' => Book::TYPE_BOOK,
            'is_available_print' => true,
            'is_available_digital' => true,
            'digital_url' => 'https://hmihukumbrawijaya.org/books/sejarah-hmi.pdf',
            'description' => 'Buku yang membahas perjalanan sejarah Himpunan Mahasiswa Islam dari awal berdiri hingga masa kini.',
            'loan_duration_days' => 14,
        ]);

        Book::create([
            'title' => 'Metodologi Penelitian Hukum Islam',
            'author' => 'Dr. Abdul Manan',
            'type' => Book::TYPE_BOOK,
            'is_available_print' => true,
            'is_available_digital' => true,
            'digital_url' => 'https://hmihukumbrawijaya.org/books/metodologi-hukum-islam.pdf',
            'description' => 'Panduan metodologi penelitian khusus untuk bidang hukum Islam.',
            'loan_duration_days' => 21,
        ]);

        // Create works by verified users
        $verifiedUsers = User::where('is_verified', true)->get();
        
        foreach ($verifiedUsers->take(10) as $user) {
            Work::factory(random_int(1, 3))->create([
                'user_id' => $user->id,
            ]);
        }

        // Create some featured works
        Work::create([
            'title' => 'Implementasi Syariat Islam dalam Konstitusi Indonesia',
            'type' => Work::TYPE_ARTIKEL,
            'user_id' => $pengurus1->id,
            'is_available_print' => false,
            'is_available_digital' => true,
            'digital_url' => 'https://hmihukumbrawijaya.org/works/syariat-konstitusi.pdf',
            'description' => 'Artikel yang membahas bagaimana syariat Islam dapat diimplementasikan dalam kerangka konstitusi Indonesia.',
        ]);

        Work::create([
            'title' => 'Peran Mahasiswa dalam Reformasi Hukum Indonesia',
            'type' => Work::TYPE_ESAI,
            'user_id' => $pengurus2->id,
            'is_available_print' => false,
            'is_available_digital' => true,
            'digital_url' => 'https://hmihukumbrawijaya.org/works/mahasiswa-reformasi.pdf',
            'description' => 'Esai tentang peran strategis mahasiswa dalam mendorong reformasi sistem hukum Indonesia.',
        ]);

        // Create book loans
        $books = Book::where('is_available_print', true)->get();
        $kaderUsers = User::where('level', User::LEVEL_KADER)->where('is_verified', true)->get();
        
        foreach ($kaderUsers->take(8) as $user) {
            if ($books->isNotEmpty()) {
                BookLoan::factory()->create([
                    'user_id' => $user->id,
                    'book_id' => $books->random()->id,
                    'verified_by' => $admin->id,
                ]);
            }
        }

        // Create some pending loans
        foreach ($kaderUsers->take(3) as $user) {
            if ($books->isNotEmpty()) {
                BookLoan::factory()->pending()->create([
                    'user_id' => $user->id,
                    'book_id' => $books->random()->id,
                ]);
            }
        }

        // Create overdue loans
        foreach ($kaderUsers->take(2) as $user) {
            if ($books->isNotEmpty()) {
                BookLoan::factory()->overdue()->create([
                    'user_id' => $user->id,
                    'book_id' => $books->random()->id,
                    'verified_by' => $admin->id,
                ]);
            }
        }

        // Create attendances
        $allUsers = User::where('is_verified', true)->get();
        
        // Create bidang attendances
        foreach ([
            Attendance::BIDANG_PEMBINAAN_ANGGOTA,
            Attendance::BIDANG_LITBANG,
            Attendance::BIDANG_P2K,
            Attendance::BIDANG_PP,
            Attendance::BIDANG_PTKP,
            Attendance::BIDANG_KPP,
        ] as $bidang) {
            // Create attendance record for each user
            foreach ($allUsers->take(15) as $user) {
                Attendance::factory()->bidang($bidang)->create([
                    'user_id' => $user->id,
                    'created_by' => $admin->id,
                ]);
            }
        }

        // Create program kegiatan attendances
        $programKegiatan = [
            'Seminar Nasional Hukum Islam',
            'Workshop Kepemimpinan HMI',
            'Diskusi Kebangsaan',
            'Pelatihan Jurnalistik',
        ];

        foreach ($programKegiatan as $program) {
            foreach ($allUsers->take(12) as $user) {
                Attendance::factory()->programKegiatan($program)->create([
                    'user_id' => $user->id,
                    'created_by' => fake()->randomElement([$admin->id, $pengurus1->id, $pengurus2->id]),
                ]);
            }
        }
    }
}