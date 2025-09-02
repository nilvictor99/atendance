<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Timesheet>
 */
class TimesheetFactory extends Factory
{
    public function definition(): array
    {
        $dayIn = fake()->dateTimeBetween('-1 month', 'now')->setTime(rand(8, 12), rand(0, 59));
        $dayOut = clone $dayIn;
        $dayOut->setTime(rand(16, 20), rand(0, 59));
        $hours = $dayIn->diff($dayOut)->h + ($dayIn->diff($dayOut)->i / 60);

        return [
            'calendar' => fake()->date(),
            'staff_id' => User::inRandomOrder()->first()->id ?? User::factory()->create()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'type' => fake()->randomElement(\App\Enums\TypeTimesheetEnum::values()),
            'day_in' => $dayIn,
            'day_out' => $dayOut,
            'hours' => round($hours, 2),
        ];
    }
}
