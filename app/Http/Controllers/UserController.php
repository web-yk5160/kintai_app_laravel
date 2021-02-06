<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\HTTP\Requests\UserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Attendance;
use Illuminate\Support\Facades\DB;
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
        $firstDay = Carbon::now()->firstOfMonth();
        $lastDay = $firstDay->copy()->endOfMonth();

        $dbParams = [];
        for ($i = 0; true; $i++) {
            $day = $firstDay->addDays($i);
            $firstDay = Carbon::now()->firstOfMonth();
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

        $date = Attendance::getOneMonthDays($firstDay, $lastDay);

        $viewParams = [
            'user' => $user,
            'date' => $date,
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
            'password' => $validated['password'],
        ]);
        return redirect('/show');
    }
}