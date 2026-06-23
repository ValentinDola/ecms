<?php

namespace Database\Factories;

use App\Models\Citizen;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Citizen>
 */
class CitizenFactory extends Factory
{
    protected $model = Citizen::class;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'full_name' => '',
            'date_of_birth' => fake()->date(),
            'nationality' => 'Togolese',
            'passport_number' => strtoupper(fake()->unique()->bothify('TG#######')),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->safeEmail(),
            'address_in_ghana' => fake()->streetAddress(),
            'city' => fake()->city(),
            'region' => fake()->state(),
            'registration_date' => now()->toDateString(),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
