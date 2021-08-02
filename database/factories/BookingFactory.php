<?php

namespace Database\Factories;

use App\Models\Room;
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
            'starts_at' => $starts_at = Carbon::now()->setHour($this->faker->numberBetween(12, 18))->addDays(rand(1, 21)),
            'ends_at' => $starts_at->clone()->addHours($this->faker->numberBetween(2, 4)),
            'product_name' => 'Dummy',
            'quantity' => $this->faker->numberBetween(20, 50),
            'unit_price' => 0,
            'is_flat' => $this->faker->numberBetween(0, 1),
            'vat' => $this->faker->numberBetween(7, 20),
            'snapshot' => '{}',
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'room_id' => Room::factory(),
        ];
    }
}
