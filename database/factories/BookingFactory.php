<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Booking;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'starts_at' => $start = $this->randomDateTime(),
            'ends_at' => $start->addHours(rand(1, 3)),
            'quantity' => rand(10, 30),
            'product_id' => Product::factory(),
            'product_snapshot' => json_encode([]),
            'order_id' => Order::factory()
        ];
    }

    public function randomDateTime(string $start = '+2 days', string $end = '+2 weeks')
    {
        $datetime = $this->faker->dateTimeBetween($start, $end);
        return Carbon::createFromTimestamp($datetime->getTimeStamp())->minute(0)->second(0);
    }
}
