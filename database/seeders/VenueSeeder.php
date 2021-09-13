<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\User;
use App\Models\Venue;
use App\Models\Package;
use App\Models\Product;
use Faker\Provider\ar_JO\Text;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VenueSeeder extends Seeder
{
    public function run()
    {
        $manager1 = User::where('email', 'manni@bapp.de')->first();
        $manager2 = User::where('email', 'maria@bapp.de')->first();
        $employee1 = User::where('email', 'emily@bapp.de')->first();
        $employee2 = User::where('email', 'erwin@bapp.de')->first();

        // $v1->users()->attach([$manager1->id, $employee1->id]);

        // Venue 2
        // $v2 = Venue::factory()->create(['name' => 'Seemagie']);

        // $v2->users()->attach([$admin->id, $manager1->id, $manager2->id, $employee1->id, $employee2->id]);

        // $room1 = Room::factory()->create(['name' => 'Schloss', 'venue_id' => $v2->id]);
        // $room2 = Room::factory()->create(['name' => 'Garten', 'venue_id' => $v2->id]);
        // $room3 = Room::factory()->create(['name' => 'Tretboot', 'venue_id' => $v2->id]);
        // $p1 = Package::factory()->create(['name' => 'Hochzeit am See', 'venue_id' => $v2->id]);
        // $p2 = Package::factory()->create(['name' => 'Hochzeit mit Flat', 'venue_id' => $v2->id]);
        // $p3 = Package::factory()->create(['name' => 'Geburtstag im Tretboot', 'venue_id' => $v2->id]);

        // $p1->rooms()->attach([$room1->id, $room2->id]);
        // $p2->rooms()->attach([$room3->id]);
    }
}
