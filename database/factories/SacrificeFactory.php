<?php

namespace Database\Factories;

use App\Models\Sacrifice;
use Illuminate\Database\Eloquent\Factories\Factory;

class SacrificeFactory extends Factory
{
    protected $model = Sacrifice::class;

    public function definition(): array
    {
        return [
            'reference_code' => 'NPC-' . date('Y') . '-' . str_pad($this->faker->unique()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT),
            'sacrifice_type' => $this->faker->randomElement(['palestina', 'nusantara']),
            'donor_name' => $this->faker->name(),
            'donor_email' => $this->faker->optional(0.8)->safeEmail(),
            'donor_phone' => $this->faker->optional(0.8)->numerify('08##########'),
            'animal_type' => 'sapi',
            'animal_price' => $this->faker->numberBetween(5_000_000, 50_000_000),
            'sharing_type' => 'full',
            'share_ratio' => 1,
            'status_purchase' => 'pending',
            'status_slaughter' => 'pending',
            'status_on_way' => 'pending',
            'status_distribution' => 'pending',
            'status_report' => 'pending',
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_purchase' => 'completed',
            'status_slaughter' => 'completed',
            'status_on_way' => 'completed',
            'status_distribution' => 'completed',
            'status_report' => 'completed',
            'date_purchase_completed' => now()->subDays(10)->toDateString(),
            'date_slaughter_completed' => now()->subDays(8)->toDateString(),
            'date_on_way_completed' => now()->subDays(6)->toDateString(),
            'date_distribution_completed' => now()->subDays(4)->toDateString(),
            'date_report_completed' => now()->subDays(2)->toDateString(),
        ]);
    }
}
