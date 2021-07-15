<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\Venue;
use App\Models\Booking;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Database\Seeders\RoleAndPermissionSeeder;

class DevSeeder extends Seeder
{
    public function run()
    {
        // Users
        $admin = User::first();

        $emily = User::factory()->create([
            'name' => 'Emily Employee',
            'email' => 'emily@bapp.de',
        ]);

        $erwin = User::factory()->create([
            'name' => 'Erwin Employee',
            'email' => 'erwin@bapp.de',
        ]);

        $emily->assignRole('user');
        $erwin->assignRole('user');

        // Venues
        $v1 = Venue::factory()->create(['name' => 'Hüttenzauber']);
        $v2 = Venue::factory()->create(['name' => 'Budenmagie']);
        $v3 = Venue::factory()->create(['name' => 'Barackenhexerei']);
        $v4 = Venue::factory()->create(['name' => 'Kabäuschenhäuschen']);

        $v1->users()->attach([$admin->id, $emily->id]);
        $v2->users()->attach([$emily->id, $erwin->id]);
        $v3->users()->attach([$admin->id, $emily->id, $erwin->id]);

        // Products
        $v1p1 = Product::factory()->create(['name' => 'Mega-Menü', 'venue_id' => $v1->id]);
        $v1p2 = Product::factory()->create(['name' => 'Mega-Menü All Flat', 'venue_id' => $v1->id]);
        $v1p3 = Product::factory()->create(['name' => 'Curlingbahn', 'venue_id' => $v1->id]);

        $v2p1 = Product::factory()->create(['name' => 'Glühweinsteak-Frenzy', 'venue_id' => $v2->id]);
        $v2p2 = Product::factory()->create(['name' => 'Punschbratenmenü', 'venue_id' => $v2->id]);
        $v2p3 = Product::factory()->create(['name' => 'Spekulatiusparty', 'venue_id' => $v2->id]);
        $v2p4 = Product::factory()->create(['name' => 'Nikloausschmaus', 'venue_id' => $v2->id]);

        $v3p1 = Product::factory()->create(['name' => 'Gammler mit ganzem Weck', 'venue_id' => $v3->id]);
        $v3p2 = Product::factory()->create(['name' => 'Gammler mit halbem Weck', 'venue_id' => $v3->id]);
        $v3p3 = Product::factory()->create(['name' => 'Gammer ganz ohne Weck', 'venue_id' => $v3->id]);

        // Orders
        // Venue 1 Order 1
        $o1 = Order::factory()->create();
        $o1b1 = Booking::factory()->create([
            'product_id' => $v1p1->id, 'order_id' => $o1->id, 'quantity' => 25,
            'starts_at' => '2021-07-13 15:00:00', 'ends_at' => '2021-07-13 18:00:00',
        ]);
        $o1b2 = Booking::factory()->create([
            'product_id' => $v1p3->id, 'order_id' => $o1->id, 'quantity' => 25,
            'starts_at' => '2021-07-13 18:00:00', 'ends_at' => '2021-07-13 20:00:00',
        ]);

        // Venue 1 Order 2
        $o2 = Order::factory()->create();
        $o1b3 = Booking::factory()->create([
            'product_id' => $v1p2->id, 'order_id' => $o2->id, 'quantity' => 15,
            'starts_at' => '2021-07-13 16:00:00', 'ends_at' => '2021-07-13 19:00:00',
        ]);
        $o1b4 = Booking::factory()->create([
            'product_id' => $v1p3->id, 'order_id' => $o2->id, 'quantity' => 15,
            'starts_at' => '2021-07-13 19:00:00', 'ends_at' => '2021-07-13 22:00:00',
        ]);

        // Venue 1 Order 3
        $o3 = Order::factory()->create();
        $o1b3 = Booking::factory()->create([
            'product_id' => $v1p1->id, 'order_id' => $o3->id, 'quantity' => 20,
            'starts_at' => '2021-07-17 16:00:00', 'ends_at' => '2021-07-17 19:00:00',
        ]);

        // Venue 2 Order 1
        $o4 = Order::factory()->create();
        $o1b3 = Booking::factory()->create([
            'product_id' => $v2p1->id, 'order_id' => $o4->id, 'quantity' => 20,
            'starts_at' => '2021-07-14 16:00:00', 'ends_at' => '2021-07-14 19:00:00',
        ]);

        // Venue 2 Order 2
        $o4 = Order::factory()->create();
        $o1b3 = Booking::factory()->create([
            'product_id' => $v2p1->id, 'order_id' => $o4->id, 'quantity' => 40,
            'starts_at' => '2021-07-17 16:00:00', 'ends_at' => '2021-07-17 19:00:00',
        ]);
    }
}
