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
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();
        $fullName = sprintf('%s %s', $firstName, $lastName);

        return [
            'name' => $fullName,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'phone' => fake()->unique()->e164PhoneNumber(),
            'job' => fake()->jobTitle(),
            'avatar' => null,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Create a default user admin
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function admin(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'John Doe',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'johndoe@eis.com',
                'phone' => '01923456789',
                'job' => 'Software Engineer',
            ];
        });
    }

    /**
     * Create a default user admin
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function employee(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Juan Cruz',
                'first_name' => 'Juan',
                'last_name' => 'Cruz',
                'email' => 'juancruz@eis.com',
                'phone' => '09112224321',
                'job' => 'Accountant II',
            ];
        });
    }
}
