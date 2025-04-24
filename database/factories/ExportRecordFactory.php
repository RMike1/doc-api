<?php

namespace Database\Factories;

use App\Enums\ExportableType;
use App\Enums\ExportStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExportRecord>
 */
class ExportRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'file_path' => fake()->filePath('exports'),
            'status' => fake()->randomElement(ExportStatus::cases()),
            'type' => fake()->randomElement(ExportableType::cases()),
        ];
    }
}
