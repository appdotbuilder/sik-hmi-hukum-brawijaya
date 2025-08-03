<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'level' => fake()->randomElement(['administrator', 'pengurus', 'kader']),
            'nik' => fake()->boolean(70) ? 'HMI-' . fake()->unique()->numerify('####-####') : null,
            'profile_photo' => null,
            'komisariat' => 'Hukum Brawijaya',
            'jurusan' => 'Hukum',
            'pt' => 'Universitas Brawijaya',
            'golongan_darah' => fake()->randomElement(['A', 'B', 'AB', 'O']),
            'no_whatsapp' => fake()->phoneNumber(),
            'alamat_malang' => fake()->address(),
            'is_verified' => fake()->boolean(80),
            'profile_completed' => fake()->boolean(75),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
