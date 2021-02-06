<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
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
}