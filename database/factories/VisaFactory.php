<?php

namespace Database\Factories;

use App\Models\Citizen;
use App\Models\Visa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Visa>
 */
class VisaFactory extends Factory
{
    protected $model = Visa::class;

    public function definition(): array
    {
        return [
            'citizen_id' => Citizen::factory(),
            'visa_number' => strtoupper(fake()->unique()->bothify('V-####-#####')),
            'passport_number' => strtoupper(fake()->bothify('TG#######')),
            'applicant_first_name' => fake()->firstName(),
            'applicant_last_name' => fake()->lastName(),
            'visa_type' => fake()->randomElement(array_keys(Visa::TYPES)),
            'issue_date' => now()->subMonth()->toDateString(),
            'expiry_date' => now()->addMonths(3)->toDateString(),
            'status' => fake()->randomElement(array_keys(Visa::STATUSES)),
            'purpose_of_visit' => fake()->optional()->sentence(),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
