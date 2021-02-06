<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function getOneMonthDays($firstDay, $lastDay)
    {
        // 1ヶ月分の日付を取得
        $oneMonthDays = self::where('attendance_day', '>=', $firstDay)
                            ->where('attendance_day', '<=', $lastDay)
                            ->get();
        return $oneMonthDays;
    }
}