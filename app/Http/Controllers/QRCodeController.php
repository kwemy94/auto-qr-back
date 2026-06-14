<?php

namespace App\Http\Controllers;

use App\Events\QRCodeScannedEvent;
use Exception;
use Illuminate\Http\Request;

class QRCodeController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function notify(Request $request)
    {
        $request->validate([
            'qrcode' => 'required|string',
            'message' => 'required|string',
        ]);

        try {
            $user = $this->userRepository->getByQr($request->qr_code);
            if(!$user){
                return response()->json(['message' => 'Propriétaire non trouvé'], 404);
            }
            # Événement WebSocket émis à l'utilisateur
            event(new QRCodeScannedEvent($user->id, $request->message));

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json([
                'message' => "Erreur survenue"
            ],500);
            
        }
    }
}
