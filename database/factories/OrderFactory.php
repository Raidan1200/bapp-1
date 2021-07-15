<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status' => ['new', 'confirmed', 'paid'][rand(0, 2)],
            'cash_payment' => rand(0, 1),
            'customer' => [
                'name' => $this->faker->name(),
                'address' => $this->faker->address(),
                'phone' => $this->faker->phoneNumber(),
            ]
        ];
    }
}
