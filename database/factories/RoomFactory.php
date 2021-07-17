<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Room::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => ucwords($this->faker->words(2, true)) . (rand(0, 1) ? ' Room' : ' Room'),
            'slogan' => $this->faker->words(5, true),
            'description' => $this->faker->paragraphs(4, true),
            'image' => null,
            'capacity' => rand(5, 15) * 10,
            'venue_id' => Venue::factory(),
        ];
    }
}
