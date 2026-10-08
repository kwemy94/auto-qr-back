<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Mail\PasswordResetCodeMail;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Réinitialisation du mot de passe par code à 6 chiffres envoyé par e-mail.
 * Le code est stocké haché dans la table password_reset_tokens.
 */
class PasswordResetController extends Controller
{
    private const CODE_TTL_MINUTES = 15;

    /**
     * POST /api/auth/forgot-password  { email }
     * Répond toujours la même chose, que le compte existe ou non,
     * pour ne pas révéler quelles adresses sont inscrites.
     */
    public function sendCode(ForgotPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $code = (string) random_int(100000, 999999);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                ['token' => Hash::make($code), 'created_at' => now()],
            );

            try {
                Mail::to($user->email)->send(
                    new PasswordResetCodeMail($code, self::CODE_TTL_MINUTES)
                );
            } catch (\Throwable $e) {
                Log::error('Envoi du code de réinitialisation échoué : ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => __('mobile.reset_code_sent'),
        ]);
    }

    /**
     * POST /api/auth/reset-password  { email, code, password, password_confirmation }
     */
    public function reset(ResetPasswordRequest $request)
    {
        $row = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        $valid = $row
            && Carbon::parse($row->created_at)->addMinutes(self::CODE_TTL_MINUTES)->isFuture()
            && Hash::check($request->code, $row->token);

        $user = $valid ? User::where('email', $request->email)->first() : null;

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => __('mobile.reset_code_invalid'),
            ], 422);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'success' => true,
            'message' => __('mobile.reset_success'),
        ]);
    }
}
