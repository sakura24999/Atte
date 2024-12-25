<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{

    public function input()
    {
        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('date', today())
            ->latest()
            ->first();

        $isWorking = $attendance && $attendance->start_time && !$attendance->end_time;
        $isOnBreak = $attendance && ($attendance->break_start && (is_string($attendance->break_start) || $attendance->break_start instanceof Carbon)) && $attendance->status === 'on_break';

        $buttonStates = [
            'workStartButton' => !$isWorking,
            'workEndButton' => $isWorking && !$isOnBreak,
            'breakStartButton' => $isWorking && !$isOnBreak,
            'breakEndButton' => $isOnBreak
        ];

        Log::info('Attendance Debug', [
            'attendance' => $attendance,
            'break_start' => $attendance->break_start ?? null,
            'break_end' => $attendance->break_end ?? null,
            'status' => $attendance->status ?? null
        ]);

        Log::info('Button States', [
            'isWorking' => $isWorking,
            'isOnBreak' => $isOnBreak,
            'buttonStates' => $buttonStates
        ]);

        return view('input', compact('buttonStates'));
    }

    public function index(Request $request)
    {
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();
        $previousDay = $date->copy()->subDay()->format('Y-m-d');
        $nextDay = $date->copy()->addDay()->format('Y-m-d');

        $attendances = Attendance::where('user_id', auth()->id())->whereDate('date', $date->format('Y-m-d'))->orderBy('start_time', 'desc')->paginate(5)->onEachSide(2);

        return view('attendance', compact('attendances', 'date', 'previousDay', 'nextDay'));
    }

    public function clockIn(Request $request)
    {
        try {
            $lastAttendance = Attendance::where('user_id', auth()->id())->whereDate('date', today())->latest()->first();

            if ($lastAttendance && $lastAttendance->start_time && !$lastAttendance->end_time) {
                return redirect()->back() - with('error', '既に勤務中です');
            }

            $attendance = new Attendance();
            $attendance->user_id = auth()->id();
            $attendance->date = now()->format('Y-m-d');
            $attendance->start_time = now()->format('H:i:s');
            $attendance->end_time = null;
            $attendance->status = 'working';

            Log::debug('Before saving attendance', [
                'start_time' => $attendance->start_time,
                'date' => $attendance->date,
                'user_id' => $attendance->user_id
            ]);

            $attendance->save();

            Log::debug('After saving attendance', [
                'id' => $attendance->id,
                'start_time' => $attendance->start_time,
                'date' => $attendance->date,
                'user_id' => $attendance->user_id
            ]);

            return redirect()->back()
                ->with('success', '勤務開始を記録しました');
        } catch (\Exception $e) {
            Log::error('Clock in error:' . $e->getMessage());
            return redirect()->back()
                ->with('error', '勤務開始に失敗しました');
        }
    }

    public function clockOut()
    {
        try {
            $attendance = Attendance::where('user_id', auth()->id())
                ->whereDate('date', today())->whereNotNull('start_time')->whereNull('end_time')->latest()
                ->first();

            if (!$attendance) {
                return redirect()->back()
                    ->with('error', '勤務開始記録がありません');
            }

            $startTime = Carbon::parse($attendance->start_time);
            $endTime = now();
            $workTime = $endTime->diffInSeconds($startTime);

            $actualWorkTime = $workTime - ($attendance->break_time ?? 0);

            $attendance->end_time = $endTime;
            $attendance->total_work_time = $actualWorkTime;
            $attendance->status = 'ended';
            $attendance->save();

            Log::debug('Clock Out Process Details:', [
                'attendance_id' => $attendance->id,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'work_time_seconds' => $workTime,
                'break_time_seconds' => $attendance->break_time,
                'actual_work_time_seconds' => $actualWorkTime,
                'total_work_time_saved' => $attendance->total_work_time
            ]);

            return redirect()->back()
                ->with('success', '勤務を終了しました');
        } catch (\Exception $e) {

            Log::error('Clock Out Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'エラーが発生しました');
        }
    }

    public function breakStart()
    {
        try {
            $attendance = Attendance::where('user_id', auth()->id())
                ->whereDate('date', today())->whereNotNull('start_time')->whereNull('end_time')->latest()
                ->first();

            Log::debug('Break Start Attempt:', [
                'attendance' => $attendance,
                'current_time' => now()
            ]);

            if (!$attendance) {
                return redirect()->back()
                    ->with('error', '勤務が開始されていません');
            }

            if ($attendance->break_start && !$attendance->break_end) {
                return redirect()->back()
                    ->with('error', '既に休憩中です');
            }

            $attendance->break_start = now()->format('H:i:s');
            $attendance->status = 'on_break';
            $attendance->save();

            Log::debug('Break Startd Successfully:', [
                'attendance_id' => $attendance->id,
                'break_start' => $attendance->break_start
            ]);

            return redirect()->back()
                ->with('success', '休憩を開始しました');
        } catch (\Exception $e) {

            Log::error('Break Start Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'エラーが発生しました');
        }
    }

    public function breakEnd()
    {
        try {
            $attendance = Attendance::where('user_id', auth()->id())
                ->whereDate('date', today())->where('status', 'on_break')->latest()
                ->first();

            Log::debug('Break End Attempt:', [
                'attendance' => $attendance,
                'current_time' => now()
            ]);

            if (!$attendance || !$attendance->break_start) {
                return redirect()->back()
                    ->with('error', '休憩開始記録がありません');
            }


            $breakStart = Carbon::parse($attendance->break_start);
            $breakEnd = now();
            $breakTime = $breakEnd->diffInSeconds($breakStart);

            $attendance->break_time = ($attendance->break_time ?? 0) + $breakTime;
            $attendance->break_start = null;
            $attendance->break_end = $breakEnd->format('H:i:s');
            $attendance->status = 'working';
            $attendance->save();

            Log::debug('Break Ended Successfully:', [
                'attendance_id' => $attendance->id,
                'break_end' => $attendance->break_end,
                'break_time' => $breakTime
            ]);

            return redirect()->back()
                ->with('success', '休憩を終了しました');
        } catch (\Exception $e) {

            Log::error('Break End Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'エラーが発生しました');
        }
    }

    private function calculateWorkTime($attendance)
    {
        $start = Carbon::parse($attendance->start_time);
        $end = Carbon::parse($attendance->end_time);
        $breakTime = $attendance->break_time ?? 0;

        return $end->diffInSeconds($start) - $breakTime;
    }
}
