<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // $table->string('name');
    // $table->string('description');
    // $table->bigInteger('price');
    // $table->bigInteger("storage_amount");

    public function definition(): array
    {
        return [
            "name" => $this->faker->word,
            "description" => $this->faker->sentence,
            "price" => $this->faker->randomFloat(2, 0, 1000),
            "category_id" => $this->faker->numberBetween(1, 5),
            "storage_amount" => $this->faker->numberBetween(1, 10),
        ];
    }
}
