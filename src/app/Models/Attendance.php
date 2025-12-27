<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start',
        'end',
        'day',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function breakTimes()
    {
        return $this->hasMany(BreakTime::class);
    }

    public function corrections()
    {
        return $this->hasMany(Correction::class);
    }

    // App\Models\Attendance.php
    protected $casts = [
        'day'   => 'date',
        'start' => 'datetime:H:i',
        'end'   => 'datetime:H:i',
    ];

    protected $appends = ['break_time', 'total_time'];

    // 休憩時間（分 → H:i）
    public function getBreakTimeAttribute()
    {
        $minutes = $this->breakTimes->sum(function ($break) {
            if ($break->start && $break->end) {
                return Carbon::parse($break->start)
                    ->diffInMinutes(Carbon::parse($break->end));
            }
            return 0;
        });

        return $minutes
            ? sprintf('%d:%02d', intdiv($minutes, 60), $minutes % 60)
            : '';
    }

    // 合計勤務時間
    public function getTotalTimeAttribute()
    {
        if (!$this->start || !$this->end) {
            return '';
        }

        $workMinutes = Carbon::parse($this->start)
            ->diffInMinutes(Carbon::parse($this->end));

        $breakMinutes = $this->breakTimes->sum(function ($break) {
            if ($break->start && $break->end) {
                return Carbon::parse($break->start)
                    ->diffInMinutes(Carbon::parse($break->end));
            }
            return 0;
        });

        $total = $workMinutes - $breakMinutes;

        return sprintf('%d:%02d', intdiv($total, 60), $total % 60);
    }

}
