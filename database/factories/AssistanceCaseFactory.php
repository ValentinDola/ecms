<?php

namespace Database\Factories;

use App\Models\AssistanceCase;
use App\Models\Citizen;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AssistanceCase>
 */
class AssistanceCaseFactory extends Factory
{
    protected $model = AssistanceCase::class;

    public function definition(): array
    {
        return [
            'case_number' => 'CA-'.now()->format('Y').'-'.str_pad((string) fake()->unique()->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT),
            'citizen_id' => Citizen::factory(),
            'case_type' => fake()->randomElement(array_keys(AssistanceCase::TYPES)),
            'status' => fake()->randomElement(array_keys(AssistanceCase::STATUSES)),
            'opened_at' => now()->subDays(3),
            'closed_at' => null,
            'description' => fake()->paragraph(),
            'actions_taken' => fake()->optional()->paragraph(),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function closed(): static
    {
        return $this->state(fn () => [
            'status' => 'closed',
            'closed_at' => now(),
        ]);
    }
}
