<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\Venue;
use App\Models\Product;
use Illuminate\Support\Str;
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
            'name' => $name = ucwords($this->faker->words(2, true)) . (rand(0, 1) ? ' Menu' : ' Buffet'),
            // 'slug' => Str::of($name)->slug('-'),
            'slogan' => $this->faker->words(5, true),
            'description' => $this->faker->paragraphs(4, true),
            'image' => null,

            'starts_at' => Carbon::now()->subMonth()->hour(0)->minute(0)->second(0),
            'ends_at' => Carbon::now()->addMonth()->hour(23)->minute(59)->second(59),
            'opens_at' => $this->faker->numberBetween(10, 12) . ':00:00',
            'closes_at' => $this->faker->numberBetween(20, 23) . ':00:00',

            'min_occupancy' => mt_rand(0, 1) ? $this->faker->numberBetween(10, 20) : 0,
            'unit_price' => ($unit_price = $this->faker->numberBetween(20, 50)) . '00',
            'vat' => '19',
            'deposit' => mt_rand(0, 1) ? '20' : '40',
            'is_flat' => mt_rand(0, 1),

            'price_flat' => ($unit_price + 30) . '00',
            'vat_flat' => '7',
            'deposit_flat' => mt_rand(0, 1) ? '20' : '40',

            'venue_id' => Venue::factory(),
        ];
    }
}
