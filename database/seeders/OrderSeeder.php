<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Venue;
use App\Models\Booking;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $zauber = Venue::with(['rooms', 'products'])->first();
        $this->makeZauber($zauber);
    }

    public function makeZauber($venue) {
        $rooms = $venue->rooms;
        $products = $venue->products;

        for ($i = 1; $i <= 10; $i++) {
            $order = Order::factory()->create([
                'venue_id' => $venue->id
            ]);
            Booking::factory()->create([
                'room_id' => $rooms[0]->id,
                'product_id' => $products[0]->id,
                'product_name' => $products[0]->name,
                'unit_price' => $products[0]->unit_price,
                'quantity' => rand(30, 50),
                'order_id' => $order->id,
            ]);
        }

        for ($i = 1; $i <= 10; $i++) {
            $order = Order::factory()->create([
                'venue_id' => $venue->id
            ]);
            Booking::factory()->create([
                'room_id' => $rooms[0]->id,
                'product_id' => $products[1]->id,
                'product_name' => $products[1]->name,
                'unit_price' => $products[1]->unit_price,
                'quantity' => rand(30, 50),
                'order_id' => $order->id,
            ]);
        }

        for ($i = 1; $i <= 10; $i++) {
            $order = Order::factory()->create([
                'venue_id' => $venue->id
            ]);
            Booking::factory()->create([
                'room_id' => $rooms[1]->id,
                'product_id' => $products[2]->id,
                'product_name' => $products[2]->name,
                'unit_price' => $products[2]->unit_price,
                'quantity' => rand(30, 50),
                'order_id' => $order->id,
            ]);
        }

        for ($i = 1; $i <= 20; $i++) {
            $start = Carbon::now()->setHour(17 + rand(0, 2))->addDays(rand(1, 4));

            $order = Order::factory()->create([
                'venue_id' => $venue->id,
                'starts_at' => $start,
            ]);
            Booking::factory()->create([
                'room_id' => $rooms[0]->id,
                'product_id' => $products[0]->id,
                'product_name' => $products[0]->name,
                'unit_price' => $products[0]->unit_price,
                'starts_at' => $start,
                'ends_at' => $end = $start->addHours(rand(1, 2)),
                'order_id' => $order->id,
                'quantity' => 25,
            ]);
            Booking::factory()->create([
                'room_id' => $rooms[1]->id,
                'product_id' => $products[2]->id,
                'product_name' => $products[2]->name,
                'unit_price' => $products[2]->unit_price,
                'starts_at' => $end,
                'ends_at' => $end->addHours(rand(1, 2)),
                'order_id' => $order->id,
                'quantity' => rand(2, 4),
            ]);
        }
    }

    public function makeSee($venue) {
        $rooms = $venue->rooms;
        $products = $venue->products;

        for ($i = 1; $i <= 10; $i++) {
            $order = Order::factory()->create([
                'venue_id' => $venue->id
            ]);
            Booking::factory()->create([
                'room_id' => $rooms[0]->id,
                'product_id' => $products[0]->id,
                'product_name' => $products[0]->name,
                'unit_price' => $products[0]->unit_price,
                'quantity' => rand(30, 50),
                'order_id' => $order->id,
            ]);
        }

        for ($i = 1; $i <= 10; $i++) {
            $order = Order::factory()->create([
                'venue_id' => $venue->id
            ]);
            Booking::factory()->create([
                'room_id' => $rooms[1]->id,
                'product_id' => $products[1]->id,
                'product_name' => $products[1]->name,
                'unit_price' => $products[1]->unit_price,
                'quantity' => rand(30, 50),
                'order_id' => $order->id,
            ]);
        }

        for ($i = 1; $i <= 10; $i++) {
            $order = Order::factory()->create([
                'venue_id' => $venue->id
            ]);
            Booking::factory()->create([
                'room_id' => $rooms[2]->id,
                'product_id' => $products[2]->id,
                'product_name' => $products[2]->name,
                'unit_price' => $products[2]->unit_price,
                'quantity' => rand(30, 50),
                'order_id' => $order->id,
            ]);
        }

        for ($i = 1; $i <= 20; $i++) {
            $order = Order::factory()->create([
                'venue_id' => $venue->id
            ]);
            Booking::factory()->create([
                'room_id' => $rooms[1]->id,
                'product_id' => $products[0]->id,
                'product_name' => $products[0]->name,
                'unit_price' => $products[0]->unit_price,
                'starts_at' => $start = Carbon::now()->setHour(17 + rand(0, 2))->addDays(rand(1, 4)),
                'ends_at' => $end = $start->addHours(rand(1, 2)),
                'order_id' => $order->id,
                'quantity' => 25,
            ]);
            Booking::factory()->create([
                'room_id' => $rooms[2]->id,
                'product_id' => $products[2]->id,
                'product_name' => $products[2]->name,
                'unit_price' => $products[2]->unit_price,
                'starts_at' => $end,
                'ends_at' => $end->addHours(rand(1, 2)),
                'order_id' => $order->id,
                'quantity' => rand(2, 4),
            ]);
        }
    }
}
