<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\HTTP\Requests\UserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = auth()->user();
        $beginningOfMonth = Carbon::now()->firstOfMonth();
        $endOfMonth = $beginningOfMonth->copy()->endOfMonth();

        for ($i = 0; true; $i++) {
            $date = $beginningOfMonth->addDays($i);
            if ($date > $endOfMonth) {
                break;
            }
        }

        $viewParams = ['user' => $user];
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