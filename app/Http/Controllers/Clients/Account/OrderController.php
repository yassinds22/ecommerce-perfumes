<?php

namespace App\Http\Controllers\Clients\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()->latest()->paginate(10);
        return view('clints.account.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = auth()->user()->orders()->with('items.product')->findOrFail($id);
        return view('clints.account.orders.show', compact('order'));
    }
}
