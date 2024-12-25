<?php

namespace Database\Seeders;

use App\Models\Attendance;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UpdateBreakTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attendances = Attendance::all();

        foreach($attendances as $attendance) {
            $breakStart = Carbon::parse($attendance->date)->setHour(12)->setMinute(0)->setSecond(0);

            $breakEnd = $breakStart->copy()->addHour();

            $attendance->update([
                'break_start' => $breakStart->format('H:i:s'),
                'break_end' => $breakEnd->format('H:i:s'),
                'break_time' => 60
            ]);
        }
    }
}
