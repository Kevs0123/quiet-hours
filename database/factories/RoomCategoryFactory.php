<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RoomCategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->randomElement([
            'Standard Room',
            'Deluxe Room',
            'Executive Suite',
            'Family Room',
            'Presidential Suite',
            'Twin Room',
            'Garden View Room',
            'Penthouse Suite',
        ]).' '.$this->faker->unique()->numberBetween(1, 999);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence(12),
        ];
    }
}
