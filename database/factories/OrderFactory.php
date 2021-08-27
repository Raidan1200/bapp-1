<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Venue;
use App\Models\Customer;
use Illuminate\Support\Carbon;
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
            'invoice_id' => $this->faker->date('Ymd') . rand(1000, 9999),
            'state' => ['fresh', 'deposit_paid', 'interim_paid', 'final_paid', 'cancelled'][rand(0, 4)],
            'cash_payment' => rand(0, 1),
            // 'deposit' => rand(0, 1) ? '20' : '40',
            'notes' => 'I am a note. Please pay attention to me!',
            'venue_id' => Venue::factory(),
            'customer_id' => Customer::factory(),
            'starts_at' => Carbon::now()->setHour($this->faker->numberBetween(12, 18))->addDays(rand(1, 21)),
        ];
    }
}
