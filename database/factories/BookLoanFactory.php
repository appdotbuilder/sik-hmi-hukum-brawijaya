<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\BookLoan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookLoan>
 */
class BookLoanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\BookLoan>
     */
    protected $model = BookLoan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $borrowedAt = fake()->dateTimeBetween('-1 month', 'now');
        $dueDate = (clone $borrowedAt)->modify('+14 days');
        
        return [
            'user_id' => User::factory(),
            'book_id' => Book::factory(),
            'status' => fake()->randomElement([
                BookLoan::STATUS_PENDING,
                BookLoan::STATUS_BORROWED,
                BookLoan::STATUS_RETURNED,
            ]),
            'borrowed_at' => $borrowedAt,
            'due_date' => $dueDate,
            'returned_at' => fake()->boolean(30) ? fake()->dateTimeBetween($borrowedAt, 'now') : null,
            'verified_by' => fake()->boolean(80) ? User::factory() : null,
            'verified_at' => fake()->boolean(80) ? fake()->dateTimeBetween($borrowedAt, 'now') : null,
            'notes' => fake()->boolean(30) ? fake()->sentence() : null,
        ];
    }

    /**
     * Indicate that the loan is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookLoan::STATUS_PENDING,
            'borrowed_at' => null,
            'due_date' => null,
            'returned_at' => null,
            'verified_by' => null,
            'verified_at' => null,
        ]);
    }

    /**
     * Indicate that the loan is currently borrowed.
     */
    public function borrowed(): static
    {
        $borrowedAt = fake()->dateTimeBetween('-1 month', 'now');
        $dueDate = (clone $borrowedAt)->modify('+14 days');
        
        return $this->state(fn (array $attributes) => [
            'status' => BookLoan::STATUS_BORROWED,
            'borrowed_at' => $borrowedAt,
            'due_date' => $dueDate,
            'returned_at' => null,
            'verified_by' => User::factory(),
            'verified_at' => $borrowedAt,
        ]);
    }

    /**
     * Indicate that the loan has been returned.
     */
    public function returned(): static
    {
        $borrowedAt = fake()->dateTimeBetween('-2 months', '-1 month');
        $dueDate = (clone $borrowedAt)->modify('+14 days');
        $returnedAt = fake()->dateTimeBetween($borrowedAt, 'now');
        
        return $this->state(fn (array $attributes) => [
            'status' => BookLoan::STATUS_RETURNED,
            'borrowed_at' => $borrowedAt,
            'due_date' => $dueDate,
            'returned_at' => $returnedAt,
            'verified_by' => User::factory(),
            'verified_at' => $borrowedAt,
        ]);
    }

    /**
     * Indicate that the loan is overdue.
     */
    public function overdue(): static
    {
        $borrowedAt = fake()->dateTimeBetween('-2 months', '-1 month');
        $dueDate = fake()->dateTimeBetween($borrowedAt, '-1 week');
        
        return $this->state(fn (array $attributes) => [
            'status' => BookLoan::STATUS_BORROWED,
            'borrowed_at' => $borrowedAt,
            'due_date' => $dueDate,
            'returned_at' => null,
            'verified_by' => User::factory(),
            'verified_at' => $borrowedAt,
        ]);
    }
}