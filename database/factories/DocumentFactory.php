<?php

namespace Database\Factories;

use App\Models\Citizen;
use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Document>
 */
class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        $path = 'documents/'.now()->format('Y').'/'.fake()->uuid().'.pdf';

        return [
            'documentable_type' => Citizen::class,
            'documentable_id' => Citizen::factory(),
            'title' => fake()->words(3, true),
            'category' => fake()->randomElement(array_keys(Document::CATEGORIES)),
            'file_path' => $path,
            'mime_type' => 'application/pdf',
            'file_size' => fake()->numberBetween(1024, 1048576),
            'uploaded_at' => now(),
        ];
    }
}
