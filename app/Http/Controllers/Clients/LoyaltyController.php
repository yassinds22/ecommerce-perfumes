<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{
    protected $loyaltyService;

    public function __construct(LoyaltyService $loyaltyService)
    {
        $this->loyaltyService = $loyaltyService;
    }

    /**
     * Display the user's loyalty dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        $pointsBalance = $this->loyaltyService->getUserPoints($user);
        $transactions = $this->loyaltyService->getPointsHistory($user);

        return view('clints.loyalty.index', compact('pointsBalance', 'transactions'));
    }
}
