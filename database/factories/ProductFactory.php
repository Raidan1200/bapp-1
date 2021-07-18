<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\Product;
use Illuminate\Support\Carbon;
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
            'name' => ucwords($this->faker->words(2, true)) . (rand(0, 1) ? ' Menu' : ' Buffet'),
            'slogan' => $this->faker->words(5, true),
            'description' => $this->faker->paragraphs(4, true),
            'image' => null,
            'starts_at' => Carbon::now()->subMonth()->hour(0)->minute(0)->second(0),
            'ends_at' => Carbon::now()->addMonth()->hour(23)->minute(59)->second(59),
            'opens_at' => $this->faker->numberBetween(10, 12) . ':00:00',
            'closes_at' => $this->faker->numberBetween(20, 23) . ':00:00',
            'min_occupancy' => rand(0, 1) ? $this->faker->numberBetween(10, 20) : null,
            'unit_price' => ($unit_price = $this->faker->numberBetween(20, 50)) . '00',
            'vat' => '19',
            'unit_price_flat' => ($unit_price + 30) . '00',
            'vat_flat' => '7',
            'deposit' => rand(0, 1) ? '20' : '40',
            'room_id' => Room::factory(),
        ];
    }
}
