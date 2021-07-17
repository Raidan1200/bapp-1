<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Venue;
use App\Models\Booking;
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
        $rooms = Venue::with('rooms.products')->first()->rooms;
        $huette = $rooms[0];
        $curling = $rooms[1];

        Booking::factory(5)->create([
            'room_id' => $huette->id, 'product_id' => $huette->products[0]->id, 'quantity' => rand(30, 50),
        ]);

        Booking::factory(5)->create([
            'room_id' => $huette->id, 'product_id' => $huette->products[1]->id, 'quantity' => rand(30, 50),
        ]);

        Booking::factory(5)->create([
            'room_id' => $curling->id, 'product_id' => $curling->products[0]->id, 'quantity' => rand(1, 4),
        ]);

        for ($i = 1; $i <= 10; $i++) {
            $order = Order::factory()->create();
            Booking::factory()->create([
                'starts_at' => ($start = 17 + rand(0, 2)) . ':00:00',
                'ends_at' => ($end = $start + rand(1, 2)) . ':00:00',
                'room_id' => $huette->id, 'product_id' => $huette->products[0]->id, 'order_id' => $order->id, 'quantity' => 25,
            ]);
            Booking::factory()->create([
                'starts_at' => $end . ':00:00',
                'ends_at' => ($end + rand(1, 2)) . ':00:00',
                'room_id' => $curling->id, 'product_id' => $curling->products[0]->id, 'order_id' => $order->id, 'quantity' => 25,
            ]);
        }
    }
}
