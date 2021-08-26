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
            'name' => $this->faker->words(2, true),
            'note' => null,
            'unit_price' => ($unit_price = $this->faker->numberBetween(2, 12)) . '00',
            'vat' => '20',
            'venue_id' => Venue::factory(),
        ];
    }
}
