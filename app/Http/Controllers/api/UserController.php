<?php

namespace App\Http\Controllers\api;

use App\Helpers\ImageManager;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('jwt.verify');
    }

    public function userInformation()
    {
        return response()->json([
            'user' => UserResource::make(auth()->user())
        ]);
    }

    public function logout()
    {
        $this->guard()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function updateProfile(Request $request)
    {
        if ($request->all()) {
            $user = $request->user();
            $requestData = $request->all();
            $userDetails = [];
            $excludedFields = ['_token', 'password_confirmation'];
            foreach ($requestData as $key => $value) {
                if (!in_array($key, $excludedFields) && $value !== '') {
                    if ($key == 'password') {
                        $userDetails[$key] = bcrypt($value);
                    } else if ($key == 'avatar') {
                        $imageName = ImageManager::update('profile/', auth()->user()->avatar, 'png', $request->file('avatar'));
                        // dd($imageName);
                        $userDetails['avatar'] = $imageName;
                    } else {
                        $userDetails[$key] = $value;
                    }
                }
            }

            if (!empty($userDetails)) {
                $user->update($userDetails);
                return response()->json(['message' => 'Successfully updated!', 'user' => UserResource::make(auth()->user())], 200);
            } else {
                return response()->json(['message' => 'No valid data to update'], 403);
            }
        } else {
            return response()->json(['message' => 'key is required'], 403);
        }
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }
}
