<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request){

        $request->validate([
            'email' =>'required|email',
            'password' => 'required','string','min:8',
        ]);

        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);
        if (Auth::attempt($credentials)) {
            $userInformation = Auth::user();
            $token = $token;

            return SuccessResource::make([
                'token' => $token,
                'user' => UserResource::make($userInformation),
            ]);
        }
    }
}
