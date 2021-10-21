<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Room;
use App\Models\Order;
use App\Models\Venue;
use App\Models\Booking;
use App\Models\Package;
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
        $venue = Venue::with(['rooms', 'packages'])->where('slug', 'zauber')->first();

        $r_huette = Room::where('slug', 'huettenrestaurant')->firstOrFail();
        $r_curling = Room::where('slug', 'curlingbahn')->firstOrFail();

        $p_classic = Package::where('slug', 'huettenpaket-classic')->firstOrFail();
        $p_premium = Package::where('slug', 'huettenpaket-premium')->firstOrFail();
        $p_curling = Package::where('slug', 'curlingbahn')->firstOrFail();

        $this->makeOrders($venue, 20, [[$r_huette, $p_classic]]);  // Classic
        $this->makeOrders($venue, 20, [[$r_huette, $p_premium]]);  // Premium
        $this->makeOrders($venue, 20, [[$r_huette, $p_classic], [$r_curling, $p_curling]]);  // Classic + Curling
        $this->makeOrders($venue, 20, [[$r_huette, $p_premium], [$r_curling, $p_curling]]);  // Premium + Curling

        foreach (Order::all() as $order) {
            $order->deposit_amount = $order->deposit;
            $order->interim_amount = $order->grossTotal - $order->deposit;

            $order->save();
        }
    }

    public function makeOrders(Venue $venue, int $count, array $combos)
    {
        for ($i = 1; $i <= $count; $i++) {
            $start = Carbon::create(2021, 11, 21, 12, 0, 0)
                ->addHours(rand(0, 3))
                ->addDays(rand(1, 21));

            $state = collect([['fresh'], ['deposit_paid', 'interim_paid', 'final_paid', 'cancelled']][rand(0, 1)])->random();

            $order = Order::factory()->create([
                'state' => $state,
                'cash_payment' => ! rand(0, 5),
                'venue_id' => $venue->id,
                'starts_at' => $start,
            ]);

            foreach ($combos as $combo) {
                Booking::factory()->create([
                    'room_id' => $combo[0]->id,
                    'package_id' => $combo[1]->id,
                    'package_name' => $combo[1]->name,
                    'unit_price' => $combo[1]->unit_price,
                    'starts_at' => $start,
                    'ends_at' => $end = (new Carbon($start))->addHours(rand(1, 3)),
                    'order_id' => $order->id,
                    'quantity' => rand(1, round($combo[0]->capacity / 3)),
                ]);

                $start = $end;
            };

            if (rand(0, 1)) {
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
        }
    }
}
