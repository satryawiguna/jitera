<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $contactable = $this->contactable();

        return [
            'contactable_id' => $contactable::factory(),
            'contactable_type' => $contactable,
            'nick_name' => $this->faker->firstName(),
            'full_name' => $this->faker->firstName() . ' ' . $this->faker->lastName(),
            'country' => $this->faker->country(),
            'state' => $this->faker->name(),
            'city' => $this->faker->city(),
            'address' => $this->faker->streetAddress(),
            'post_code' => $this->faker->postcode(),
            'mobile' => $this->faker->phoneNumber()
        ];
    }

    public function contactable()
    {
        return $this->faker->randomElement([
            User::class
        ]);
    }
}
