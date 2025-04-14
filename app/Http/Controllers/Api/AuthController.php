<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);
        return response()->json(['user' => $user, 'token' => $token, 'message' => 'Successful registration !']);
    }


    public function login(LoginRequest $request)
    {

        $login = $request->email ?? $request->phone;
        $password = $request->password;
        $field = $request->email ? 'email' : 'phone';

        $credentials = [$field => $login, 'password' => $password];

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return response()->json(compact('token'));
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Successful logout !']);
    }

    public function user()
    {
        $user = JWTAuth::user();
        return response()->json(compact('user'));
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = JWTAuth::user();

        $user->fill($request->only(['name', 'email', 'phone']))->save();

        return response()->json([
            'message' => 'Profile updated successfully !',
            'user'    => $user,
        ]);
    }
}
