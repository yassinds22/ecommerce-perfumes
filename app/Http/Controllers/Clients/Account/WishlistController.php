<?php

namespace App\Http\Controllers\Clients\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = auth()->user()->wishlist()->with('product')->latest()->get();
        return view('clints.account.wishlist', compact('wishlistItems'));
    }

    public function destroy($id)
    {
        $wishlistItem = auth()->user()->wishlist()->findOrFail($id);
        $wishlistItem->delete();

        return back()->with('success', 'تم إزالة المنتج من المفضلة.');
    }
}
