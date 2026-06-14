<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Notifications\ScanNotification;
use App\Notifications\ScanQRNotification;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class MessageController extends Controller
{

    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        //
    }

    public function handleScan(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        $targetUser = $this->userRepository->getByQRCode($request->qr_code);

        if (!$targetUser) {
            return response()->json(['success' => false, 'message' => 'QR code invalide.'], 404);
        }
        $targetUser->notify(new ScanQRNotification());
        return response()->json(['success' => true, 'owner' => $targetUser, 'message' => 'Scan enregistré']);
    }
}
