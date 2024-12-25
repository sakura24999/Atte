<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'clock_in',
        'clock_out',
        'start_time',
        'end_time',
        'break_start',
        'break_end',
        'break_time',
        'total_work_time',
        'status',
        'comments'
    ];

    // 日付を自動的にCarbonインスタンスに変換
    protected $dates = [
        'date',
        'start_time',
        'end_time',
        'break_start',
        'break_end',
        'created_at',
        'updated_at'
    ];

    // ユーザーとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    const STATUS_WORKING = 'working';
    const STATUS_BREAKING = 'breaking';
    const STATUS_FINISHED = 'finished';

    public function calculateTotalWorkTime()
    {
        if ($this->start_time && $this->end_time) {
            $total = $this->end_time->diffInSeconds($this->start_time);
            if ($this->break_time) {
                $total -= $this->break_time;
            }
            return $total;
        }
        return 0;
    }
}
