<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\Attendance;
use Illuminate\Http\Request;
use App\HTTP\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {

        $user = auth()->user();
        $userId = $user->id;

        if (empty($request->input('current_day'))) {
            $currentDay = Carbon::now();
        } else {
            // クエリパラメータありならバリデーション
            $validator = $this->validator($request->query());
            if ($validator->fails()) {
                return redirect('/show');
            }
            $currentDay = Carbon::parse($request->input('current_day'));
        }
        $today = Carbon::today();
        $firstDay = $currentDay->copy()->firstOfMonth();
        $lastDay = $firstDay->copy()->endOfMonth();
        $lastMonth = $currentDay->copy()->subMonthNoOverflow()->format('Y-m-d');
        $nextMonth = $currentDay->copy()->addMonthNoOverflow()->format('Y-m-d');
        $week = ['日', '月', '火', '水', '木', '金', '土'];

        $dbParams = [];
        for ($i = 0; true; $i++) {
            $day = $firstDay->addDays($i);
            $firstDay = $currentDay->copy()->firstOfMonth();
            // テーブルに値が存在しないか確認
            if (!DB::table('attendances')->where('attendance_day', $day)->where('user_id', $userId)->exists()) {
                $data = [
                    'user_id' => $userId,
                    'attendance_day' => $day,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
            if ($day > $lastDay) {
                break;
            }

            if (!empty($data)) {
                array_push($dbParams, $data);
            }
        }

        if (!empty($dbParams)) {
            DB::transaction(function () use ($dbParams) {
                DB::table('attendances')->insert($dbParams);
            });
        }

        $date = Attendance::getOneMonthData($firstDay, $lastDay);
        // 曜日表示のため、Carbonインスタンスに変更
        foreach($date as $d) {
            $d->attendance_day = Carbon::parse($d->attendance_day);
            $d->start_time = $d->start_time ? Carbon::parse($d->start_time) : null;
            $d->end_time = $d->end_time ? Carbon::parse($d->end_time) : null;
        }

        $viewParams = [
            'user' => $user,
            'date' => $date,
            'week' => $week,
            'lastMonth' => $lastMonth,
            'nextMonth' => $nextMonth,
            'firstDay' => $firstDay,
            'lastDay' => $lastDay,
            'today' => $today,
        ];

        return view('user.show', $viewParams);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = auth()->user();
        return view('user.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::find($id);
        $validated = $request->validated();
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'belong' => $validated['belong'],
            'password' => Hash::make($validated['password']),
        ]);
        return redirect('/show');
    }

    // private
    /**
     * クエリパラメータチェック.
     *
    * @param  array  $data
    * @return \Illuminate\Contracts\Validation\Validator
    */
    public function validator(array $data)
    {
        $validator = Validator::make($data, [
            'current_day' => 'date'
        ]);
        return $validator;
    }

}