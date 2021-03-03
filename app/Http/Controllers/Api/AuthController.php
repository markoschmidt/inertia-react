<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    public function authenticate(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        if (Auth::attempt($credentials, true)) {
            return $request->session()->token();
        }

        return null;
    }
}
