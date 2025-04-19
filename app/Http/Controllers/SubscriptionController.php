<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscribeRequest;
use App\Models\Package;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Repositories\PackageRepository;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Throwable;

class SubscriptionController extends Controller
{
    protected PackageRepository $packageReposirory;
    protected UserRepository $userRepository;

    public function __construct(PackageRepository $packageReposirory, UserRepository $userRepository)
    {
        $this->packageReposirory = $packageReposirory;
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
    public function store(SubscribeRequest $request)
    {
        $user = $this->userRepository->getById($request->user_id);
        $package = $this->packageReposirory->getById($request->package_id);

        try {
            $result = $this->startSubscription($package, $user);
            return ($result['status'] == 'active_subscription')
                ? response()->json(['success' => false, 'message' => 'Continue to use your package, it hasn\'t expired yet.'])
                : response()->json(['success' => true, 'user' => $this->userRepository->getUser($user->id), 'message' => 'Subscription successfully completed.']);
        } catch (Throwable $e) {
            Log::error('Subscription error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error has occurred during the subscription process.',]);
        }
    }

    public function startSubscription(Package $package, User $user)
    {

        return DB::transaction(function () use ($package, $user) {
            $status = $this->userRepository->hasExpiredLastestSubscription($user->id);

            if ($status['expired']) {
                $this->userRepository->subscribe($package, $user->id);
                // ajouter l'action de paiement
                return ['status' => 'subscribed'];
            }
            return ['status' => 'active_subscription'];
        });
    }

    public function generateQrCode($userId)
    {
        $user = $this->userRepository->getById($userId);

        return QrCode::size(300)->generate($user->qr_code);
    }

    public function downloadQrCode($userId)
    {
        $user = $this->userRepository->getById($userId);

        $qrImage = QrCode::format('png')->size(300)->generate($user->qr_code);
        Storage::disk('public')->put('qrcodes/mon-code.png', $qrImage);

        return response()->download(storage_path('app/public/qrcodes/mon-code.png'));
    }

    public function subscribetest($package)
    {
        $user = JWTAuth::user();

        $subscriptionData = [
            'start_date' => now(),
            'end_date' => now()->addMonths($package->duration),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // 1. Génère l’abonnement via la table pivot
        $user->subscriptions()->attach($package->id, $subscriptionData);

        // 2. Générer le QR code
        $qrContent = "user:{$user->id}|package:{$package->id}|date:" . now()->toDateString();
        $qrImage = QrCode::format('png')->size(300)->generate($qrContent);

        $fileName = "qrcodes/user_{$user->id}_package_{$package->id}.png";
        Storage::disk('public')->put($fileName, $qrImage);

        // 3. Enregistre éventuellement le chemin dans une table (ex: user_package.qr_path)
        $user->subscriptions()->updateExistingPivot($package->id, [
            'qr_path' => $fileName,
        ]);
    }
}
