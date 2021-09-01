<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Order;
use App\Models\Venue;
use App\Models\Booking;
use App\Models\Product;
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
        $zauber = Venue::with(['rooms', 'packages'])->first();
        $this->makeZauber($zauber);
    }

    // TODO: DRY up code!
    public function makeZauber($venue) {
        $rooms = $venue->rooms;
        $packages = $venue->packages;

        for ($i = 1; $i <= 10; $i++) {
            $order = Order::factory()->create([
                'venue_id' => $venue->id
            ]);
            Booking::factory()->create([
                'room_id' => $rooms[0]->id,
                'package_id' => $packages[0]->id,
                'package_name' => $packages[0]->name,
                'unit_price' => $packages[0]->unit_price,
                'quantity' => rand(30, 50),
                'order_id' => $order->id,
            ]);

            for ($j = 1; $j <= rand(0, 3); $j++) {
                $product = Product::inRandomOrder()->first();
                Item::factory()->create([
                    'product_name' => $product->name,
                    'unit_price' => $product->unit_price,
                    'vat' => $product->vat,
                    'order_id' => $order->id,
                ]);
            }
        }

        for ($i = 1; $i <= 10; $i++) {
            $order = Order::factory()->create([
                'venue_id' => $venue->id
            ]);
            Booking::factory()->create([
                'room_id' => $rooms[0]->id,
                'package_id' => $packages[1]->id,
                'package_name' => $packages[1]->name,
                'unit_price' => $packages[1]->unit_price,
                'quantity' => rand(30, 50),
                'order_id' => $order->id,
            ]);

            for ($j = 1; $j <= rand(0, 3); $j++) {
                $product = Product::inRandomOrder()->first();
                Item::factory()->create([
                    'product_name' => $product->name,
                    'unit_price' => $product->unit_price,
                    'vat' => $product->vat,
                    'order_id' => $order->id,
                ]);
            }
        }

        for ($i = 1; $i <= 10; $i++) {
            $order = Order::factory()->create([
                'venue_id' => $venue->id
            ]);
            Booking::factory()->create([
                'room_id' => $rooms[1]->id,
                'package_id' => $packages[2]->id,
                'package_name' => $packages[2]->name,
                'unit_price' => $packages[2]->unit_price,
                'quantity' => rand(30, 50),
                'order_id' => $order->id,
            ]);

            for ($j = 1; $j <= rand(0, 3); $j++) {
                $product = Product::inRandomOrder()->first();
                Item::factory()->create([
                    'product_name' => $product->name,
                    'unit_price' => $product->unit_price,
                    'vat' => $product->vat,
                    'order_id' => $order->id,
                ]);
            }
        }

        for ($i = 1; $i <= 20; $i++) {
            $start = Carbon::now()->setHour(17 + rand(0, 2))->addDays(rand(1, 4));

            $order = Order::factory()->create([
                'venue_id' => $venue->id,
                'starts_at' => $start,
            ]);
            Booking::factory()->create([
                'room_id' => $rooms[0]->id,
                'package_id' => $packages[0]->id,
                'package_name' => $packages[0]->name,
                'unit_price' => $packages[0]->unit_price,
                'starts_at' => $start,
                'ends_at' => $end = $start->addHours(rand(1, 2)),
                'order_id' => $order->id,
                'quantity' => 25,
            ]);
            Booking::factory()->create([
                'room_id' => $rooms[1]->id,
                'package_id' => $packages[2]->id,
                'package_name' => $packages[2]->name,
                'unit_price' => $packages[2]->unit_price,
                'starts_at' => $end,
                'ends_at' => $end->addHours(rand(1, 2)),
                'order_id' => $order->id,
                'quantity' => rand(2, 4),
            ]);
        }
    }

    public function makeSee($venue) {
        $rooms = $venue->rooms;
        $packages = $venue->packages;

        for ($i = 1; $i <= 10; $i++) {
            $order = Order::factory()->create([
                'venue_id' => $venue->id
            ]);
            Booking::factory()->create([
                'room_id' => $rooms[0]->id,
                'package_id' => $packages[0]->id,
                'package_name' => $packages[0]->name,
                'unit_price' => $packages[0]->unit_price,
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
                'package_id' => $packages[1]->id,
                'package_name' => $packages[1]->name,
                'unit_price' => $packages[1]->unit_price,
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
                'package_id' => $packages[2]->id,
                'package_name' => $packages[2]->name,
                'unit_price' => $packages[2]->unit_price,
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
                'package_id' => $packages[0]->id,
                'package_name' => $packages[0]->name,
                'unit_price' => $packages[0]->unit_price,
                'starts_at' => $start = Carbon::now()->setHour(17 + rand(0, 2))->addDays(rand(1, 4)),
                'ends_at' => $end = $start->addHours(rand(1, 2)),
                'order_id' => $order->id,
                'quantity' => 25,
            ]);
            Booking::factory()->create([
                'room_id' => $rooms[2]->id,
                'package_id' => $packages[2]->id,
                'package_name' => $packages[2]->name,
                'unit_price' => $packages[2]->unit_price,
                'starts_at' => $end,
                'ends_at' => $end->addHours(rand(1, 2)),
                'order_id' => $order->id,
                'quantity' => rand(2, 4),
            ]);
        }
    }
}
