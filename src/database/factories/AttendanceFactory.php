<?php

namespace Database\Factories;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Carbon\Carbon;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date = $this->faker->dateTimeBetween('-30 days', 'now');

        $startTime = Carbon::instance($date)->setHour(9)->setMinute($this->faker->numberBetween(0, 30));

        $endTime = clone $startTime;
        $endTime->setHour(17 + $this->faker->numberBetween(0, 2))->setMinute($this->faker->numberBetween(0, 59));

        $breakStart = clone $startTime;
        $breakStart->setHour(12)->setMinute(0);
        $breakEnd = clone $breakStart;
        $breakEnd->addHour();

        $totalMinutes = $endTime->diffInMinutes($startTime);

        $totalWorkTime = $totalMinutes - 60;

        $breakTime = 60;

        return [
            'user_id' => User::factory(),
            'date' => $date->format('Y-m-d'),
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
            'break_start' => $breakStart->format('H:i:s'),
            'break_end' => $breakEnd->format('H:i:s'),
            'total_work_time' => 480,
            'status' => $this->faker->randomElement(['working', 'finished']),
            'comments' => $this->faker->optional()->sentence,
        ];
    }

    public function working()
    {
        return $this->state(function (array $attributes) {
            return [
                'end_time' => null,
                'break_end' => null,
                'total_work_time' => null,
                'status' => 'working',
            ];
        });
    }
}
