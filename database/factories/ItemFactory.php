<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_name' => $this->faker->words(2, true),
            'quantity' => $this->faker->numberBetween(1, 20),
            'unit_price' => ($unit_price = $this->faker->numberBetween(2, 12)) . '00',
            'vat' => '20',
            'order_id' => Order::factory(),
        ];
    }
}
