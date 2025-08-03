<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Attendance>
     */
    protected $model = Attendance::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement([Attendance::TYPE_BIDANG, Attendance::TYPE_PROGRAM_KEGIATAN]);
        
        $bidangTitles = [
            Attendance::BIDANG_PEMBINAAN_ANGGOTA => 'Rapat Koordinasi Pembinaan Anggota',
            Attendance::BIDANG_LITBANG => 'Diskusi Penelitian dan Pengembangan',
            Attendance::BIDANG_P2K => 'Pelatihan Pemberdayaan Kader',
            Attendance::BIDANG_PP => 'Evaluasi Program Pengkaderan',
            Attendance::BIDANG_PTKP => 'Workshop Pengembangan Teknologi',
            Attendance::BIDANG_KPP => 'Seminar Kerjasama dan Pengembangan',
        ];

        $programKegiatanTitles = [
            'Muktamar Nasional HMI',
            'Seminar Nasional Hukum',
            'Workshop Kepemimpinan',
            'Training of Trainer',
            'Dialog Kebangsaan',
            'Pelatihan Jurnalistik',
        ];

        if ($type === Attendance::TYPE_BIDANG) {
            $bidang = fake()->randomElement([
                Attendance::BIDANG_PEMBINAAN_ANGGOTA,
                Attendance::BIDANG_LITBANG,
                Attendance::BIDANG_P2K,
                Attendance::BIDANG_PP,
                Attendance::BIDANG_PTKP,
                Attendance::BIDANG_KPP,
            ]);
            $title = $bidangTitles[$bidang];
            $programKegiatan = null;
        } else {
            $bidang = null;
            $programKegiatan = fake()->randomElement($programKegiatanTitles);
            $title = $programKegiatan;
        }

        $date = fake()->dateTimeBetween('-1 month', '+1 week');
        $status = fake()->randomElement([
            Attendance::STATUS_PRESENT,
            Attendance::STATUS_ABSENT,
            Attendance::STATUS_LATE,
        ]);

        return [
            'user_id' => User::factory(),
            'type' => $type,
            'bidang' => $bidang,
            'program_kegiatan' => $programKegiatan,
            'title' => $title,
            'description' => fake()->paragraph(),
            'date' => $date,
            'status' => $status,
            'checked_in_at' => $status !== Attendance::STATUS_ABSENT ? $date : null,
            'created_by' => User::factory()->state(['level' => fake()->randomElement(['administrator', 'pengurus'])]),
        ];
    }

    /**
     * Indicate that the attendance is for bidang.
     */
    public function bidang(string $bidangType = null): static
    {
        $bidang = $bidangType ?? fake()->randomElement([
            Attendance::BIDANG_PEMBINAAN_ANGGOTA,
            Attendance::BIDANG_LITBANG,
            Attendance::BIDANG_P2K,
            Attendance::BIDANG_PP,
            Attendance::BIDANG_PTKP,
            Attendance::BIDANG_KPP,
        ]);

        return $this->state(fn (array $attributes) => [
            'type' => Attendance::TYPE_BIDANG,
            'bidang' => $bidang,
            'program_kegiatan' => null,
        ]);
    }

    /**
     * Indicate that the attendance is for program kegiatan.
     */
    public function programKegiatan(string $program = null): static
    {
        $programKegiatan = $program ?? fake()->randomElement([
            'Muktamar Nasional HMI',
            'Seminar Nasional Hukum',
            'Workshop Kepemimpinan',
            'Training of Trainer',
            'Dialog Kebangsaan',
            'Pelatihan Jurnalistik',
        ]);

        return $this->state(fn (array $attributes) => [
            'type' => Attendance::TYPE_PROGRAM_KEGIATAN,
            'bidang' => null,
            'program_kegiatan' => $programKegiatan,
            'title' => $programKegiatan,
        ]);
    }

    /**
     * Indicate that the user is present.
     */
    public function present(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Attendance::STATUS_PRESENT,
            'checked_in_at' => $attributes['date'],
        ]);
    }

    /**
     * Indicate that the user is absent.
     */
    public function absent(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Attendance::STATUS_ABSENT,
            'checked_in_at' => null,
        ]);
    }

    /**
     * Indicate that the user is late.
     */
    public function late(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Attendance::STATUS_LATE,
            'checked_in_at' => fake()->dateTimeBetween($attributes['date'], '+2 hours'),
        ]);
    }
}