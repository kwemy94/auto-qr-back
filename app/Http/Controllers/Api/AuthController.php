<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\DeleteAccountRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\PackageRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
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
        try {
            $user = $this->userRepository->store([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'fcm_token' => $request->fcm_token ?? null,
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'user' => $user,
                'token' => $token,
                'message' => 'Successful registration.'
            ], 201);

        } catch (\Exception $e) {
            Log::info('Erreur server : '.$e->getMessage());
            return response()->json([
                'message' => 'Erreur serveur. Veuillez réessayer.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function login(LoginRequest $request)
    {
        $login = $request->email ?? $request->phone;
        $password = $request->password;
        $field = $request->email ? 'email' : 'phone';

        $credentials = [$field => $login, 'password' => $password];

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email ou mot de passe incorrect.',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => JWTAuth::user(),
        ]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Successful logout.']);
    }

    public function user()
    {
        if (JWTAuth::user()) {
            $user = $this->userRepository->getUser(JWTAuth::user()->id);
            return response()->json(compact('user'));
        }
        return null;
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = JWTAuth::user();

        $user->fill($request->validated())->save();
        return response()->json([
            'success' => true,
            'message' => __('mobile.profile_updated'),
            'user' => $user->fresh(),
        ]);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = JWTAuth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => __('mobile.current_password_incorrect'),
                'errors' => ['current_password' => [__('mobile.current_password_incorrect')]],
            ], 422);
        }

        $user->password = Hash::make($request->new_password);
        $this->userRepository->update($user->id, ['password' => $user->password]);

        return response()->json([
            'success' => true,
            'message' => __('mobile.password_changed'),
        ]);
    }

    /**
     * DELETE /api/auth/account  { password }
     * Suppression définitive du compte (exigence Google Play).
     * Les signalements et logs liés sont supprimés en cascade ; les messages
     * de contact sont conservés de façon anonyme (user_id → null).
     */
    public function deleteAccount(DeleteAccountRequest $request)
    {
        $user = JWTAuth::user();

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => __('mobile.current_password_incorrect'),
                'errors' => ['password' => [__('mobile.current_password_incorrect')]],
            ], 422);
        }

        DB::transaction(function () use ($user) {
            // subscriptions.user_id n'a pas de suppression en cascade
            DB::table('subscriptions')->where('user_id', $user->id)->delete();

            if (!empty($user->qr_path) && Storage::disk('public')->exists($user->qr_path)) {
                Storage::disk('public')->delete($user->qr_path);
            }

            $user->delete();
        });

        try {
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (\Throwable $e) {
            // Le compte est supprimé : un échec d'invalidation du token n'est pas bloquant
        }

        return response()->json([
            'success' => true,
            'message' => __('mobile.account_deleted'),
        ]);
    }

    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => ['required', 'string', 'max:255'],
        ]);

        $request->user()->update([
            'fcm_token' => $request->fcm_token,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Token FCM mis à jour.',
        ]);
    }
}
