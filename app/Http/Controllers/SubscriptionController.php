<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscribeRequest;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\PackageRepository;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Log;
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
}
