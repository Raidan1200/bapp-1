<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->email(),
            'company' => $this->faker->company(),
            'street' => $this->faker->streetName(),
            'street_no' => $this->faker->randomNumber(3),
            'zip' => $this->faker->postcode(),
            'city' => $this->faker->city(),
            'phone' => $this->faker->phoneNumber(),
            'user_id' => User::factory(),
        ];
    }
}
