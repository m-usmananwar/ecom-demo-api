<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UserLoginRequest;
use App\Http\Requests\Users\UserRegisterRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid Email or Password',
            ], 422);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Success',
            'token' => $token,
            'user' => $user,
        ]);
    }
    public function register(UserRegisterRequest $request)
    {
        $user = User::create($request->validated());
        $user = $user->refresh();
        if ($request->hasFile('image')) {
            $user->addMediaFromRequest('image')
                ->toMediaCollection('profile-image');
        }
        return response()->json([
            'message' => 'Success',
            'user' => $user,
        ]);
    }
    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'Success',
            'Response' => 'User has been logged out'
        ]);
    }
}
