<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Work;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Work>
 */
class WorkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Work>
     */
    protected $model = Work::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $artikelTitles = [
            'Implementasi Syariat Islam dalam Sistem Hukum Indonesia',
            'Peran Mahasiswa dalam Reformasi Hukum',
            'Analisis Konstitusional Perubahan UUD 1945',
            'Hak Asasi Manusia dalam Perspektif Islam',
            'Demokrasi dan Kedaulatan Rakyat'
        ];

        $esaiTitles = [
            'Refleksi Kepemimpinan dalam Islam',
            'Makna Jihad dalam Konteks Modern',
            'Pendidikan Karakter Berbasis Nilai Islam',
            'Toleransi Beragama di Indonesia',
            'Tantangan Generasi Muslim Milenial'
        ];

        $ktiTitles = [
            'Efektivitas Hukum Pidana dalam Memberantas Korupsi',
            'Perlindungan Hukum terhadap Konsumen Online',
            'Implementasi Restorative Justice di Indonesia',
            'Analisis Yuridis Perkawinan Beda Agama',
            'Kedudukan Hukum Adat dalam Sistem Hukum Nasional'
        ];

        $type = fake()->randomElement([Work::TYPE_ARTIKEL, Work::TYPE_ESAI, Work::TYPE_KTI]);
        
        $title = match($type) {
            Work::TYPE_ARTIKEL => fake()->randomElement($artikelTitles),
            Work::TYPE_ESAI => fake()->randomElement($esaiTitles),
            Work::TYPE_KTI => fake()->randomElement($ktiTitles),
            default => fake()->randomElement($artikelTitles),
        };

        return [
            'title' => $title,
            'type' => $type,
            'user_id' => User::factory(),
            'is_available_print' => fake()->boolean(30),
            'is_available_digital' => fake()->boolean(90),
            'digital_url' => fake()->boolean(90) ? fake()->url() : null,
            'description' => fake()->paragraph(),
        ];
    }

    /**
     * Indicate that the work is an artikel.
     */
    public function artikel(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => Work::TYPE_ARTIKEL,
        ]);
    }

    /**
     * Indicate that the work is an esai.
     */
    public function esai(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => Work::TYPE_ESAI,
        ]);
    }

    /**
     * Indicate that the work is a KTI.
     */
    public function kti(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => Work::TYPE_KTI,
        ]);
    }
}