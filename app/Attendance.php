<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $dates = [
        'start_time'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function getOneMonthData($firstDay, $lastDay)
    {
        // 1ヶ月分の勤怠データを取得
        $oneMonthData = self::where('attendance_day', '>=', $firstDay)
                            ->where('attendance_day', '<=', $lastDay)
                            ->get();
        return $oneMonthData;
    }

    /**
     * 今日の日付の勤怠データを取得
     * @return App\Attendance
     */

    public static function getAttendanceTodayData($userId, $today)
    {
        $attendance = self::where('user_id', $userId)->where('attendance_day', $today)->first();
        return $attendance;
    }
}