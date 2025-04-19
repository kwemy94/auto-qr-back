<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\PackageRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->userRepository->store([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);
        return response()->json(['user' => $user, 'token' => $token, 'message' => 'Successful registration.']);
    }


    public function login(LoginRequest $request)
    {

        $login = $request->email ?? $request->phone;
        $password = $request->password;
        $field = $request->email ? 'email' : 'phone';

        $credentials = [$field => $login, 'password' => $password];

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials.'], 401);
        }

        return response()->json(['token' => $token, 'user' => JWTAuth::user()]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Successful logout.']);
    }

    public function user()
    {
        if(JWTAuth::user()){
            $user = $this->userRepository->getUser(JWTAuth::user()->id);
            return response()->json(compact('user'));
        }
        return null;
    }

    public function updateProfile(Request $request)
    {
        $user = JWTAuth::user();

        $user->fill($request->only(['name', 'email', 'phone']))->save();
        return response()->json([
            'message' => 'Profile updated successfully.',
            'user'    => $user,
        ]);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = JWTAuth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'The current password is incorrect.'
            ], 400);
        }

        $user->password = Hash::make($request->new_password);
        $this->userRepository->update($user->id, ['password' => $user->password]);

        return response()->json([
            'message' => 'Password successfully changed.'
        ]);
    }
}
