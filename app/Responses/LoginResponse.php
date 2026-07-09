<?php

namespace App\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            return redirect()->intended('/admin/dashboard');
        }

        if ($user->role === 'salesman') {
            return redirect()->intended('/salesman/dashboard');
        }

        return redirect()->intended('/home');
    }
}
