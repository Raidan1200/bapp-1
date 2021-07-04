<?php

namespace Database\Factories;

use App\Models\Venue;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(2, true) . (rand(0, 1) ? ' Menu' : ' Buffet'),
            'excerpt' => $this->faker->paragraphs(2, true),
            'description' => $this->faker->paragraphs(4, true),
            'image' => '',
            'capacity' => $this->faker->randomNumber(2, true),
            'price' => $this->faker->randomNumber(4, true),
            'deposit' => $this->faker->randomNumber(2, true),
            'opens_at' => $this->faker->numberBetween(8, 16).':00:00',
            'closes_at' => $this->faker->numberBetween(18, 24).':00:00',
            'venue_id' => Venue::factory(),
        ];
    }
}
