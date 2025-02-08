<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use App\Models\MessageCapsule;
use App\Models\User;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MessageCapsule>
 */
class MessageCapsuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = MessageCapsule::class;
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'message' => Crypt::encryptString($this->faker->sentence),
            'scheduledOpeningTime' => Carbon::now()->addDays(random_int(1, 10)),
            'opened' => false,
        ];
    }
}
