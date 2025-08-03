<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Book>
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $books = [
            'Sejarah Pergerakan Mahasiswa Islam Indonesia',
            'Metodologi Penelitian Hukum',
            'Filsafat Hukum Islam',
            'Konstitusi dan HAM',
            'Hukum Tata Negara Indonesia',
            'Pemikiran Politik Islam',
            'Etika dan Moral dalam Islam',
            'Kepemimpinan dalam Perspektif Islam',
            'Hukum Perdata Indonesia',
            'Hukum Pidana dan Kriminologi'
        ];

        $authors = [
            'Prof. Dr. H. Munawir Sjadzali',
            'Dr. Jimly Asshiddiqie',
            'Prof. Dr. Hasbi Ash-Shiddieqy',
            'Dr. Bagir Manan',
            'Prof. Dr. Satjipto Rahardjo',
            'Dr. Nurcholish Madjid',
            'Prof. Dr. Quraish Shihab',
            'Dr. Syafii Maarif',
            'Prof. Dr. Subekti',
            'Dr. Moeljatno'
        ];

        return [
            'title' => fake()->randomElement($books),
            'author' => fake()->randomElement($authors),
            'type' => Book::TYPE_BOOK,
            'is_available_print' => fake()->boolean(80),
            'is_available_digital' => fake()->boolean(60),
            'digital_url' => fake()->boolean(60) ? fake()->url() : null,
            'description' => fake()->paragraph(),
            'loan_duration_days' => fake()->randomElement([7, 14, 21]),
        ];
    }

    /**
     * Indicate that the book is available in print only.
     */
    public function printOnly(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_available_print' => true,
            'is_available_digital' => false,
            'digital_url' => null,
        ]);
    }

    /**
     * Indicate that the book is available digitally only.
     */
    public function digitalOnly(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_available_print' => false,
            'is_available_digital' => true,
            'digital_url' => fake()->url(),
        ]);
    }
}