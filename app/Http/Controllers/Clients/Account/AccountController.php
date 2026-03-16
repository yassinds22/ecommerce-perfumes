<?php

namespace App\Http\Controllers\Clients\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $recentOrders = $user->orders()->latest()->take(3)->get();
        $wishlistCount = $user->wishlist()->count();
        $defaultAddress = $user->addresses()->where('is_default', true)->first();

        return view('clints.account.index', compact('user', 'recentOrders', 'wishlistCount', 'defaultAddress'));
    }
}
