<?php

namespace Database\Factories;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

class VenueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Venue::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => ucfirst($this->faker->word()) . (rand(0, 1) ? ' Mall' : ' Place'),
            'slug' => $this->faker->word(),
            'email' => $this->faker->email(),
            'logo' => null,
            'reminder_delay' => 5,
            'check_delay' => 9,
            'check_count' => 0,
            'cancel_delay' => 12,
        ];
    }
}
