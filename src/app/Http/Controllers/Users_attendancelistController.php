<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class Users_attendancelistController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with('user')
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(5);

        $attendances->transform(function ($attendance) {
            if ($attendance->start_time && $attendance->end_time) {
                $attendance->total_work_time = Carbon::parse($attendance->end_time)
                    ->diffInSeconds(Carbon::parse($attendance->start_time));
            }
            return $attendance;
        });

        Log::debug('Detailed Attendances Debug Info:', [
            'records' => $attendances->take(3)->map(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'start_time' => $attendance->start_time,
                    'end_time' => $attendance->end_time,
                    'total_work_time' => [
                        'raw_value' => $attendance->total_work_time,
                        'expected_format' => [
                            'hours' => floor($attendance->total_work_time / 3600),
                            'minutes' => floor(($attendance->total_work_time % 3600) / 60),
                            'seconds' => $attendance->total_work_time % 60
                        ],
                        'actual_time_diff' => $attendance->end_time
                            ? Carbon::parse($attendance->end_time)->diffInSeconds(Carbon::parse($attendance->start_time))
                            : null
                    ]
                ];
            })
        ]);

        return view('management.users_attendance_list', compact('attendances'));
    }

    public function usersList()
    {
        Log::debug('usersList method called');
        return view('users_attendance_list');
    }
}
