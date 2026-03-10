<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = auth()->user()->wishlist()->with('product.media')->latest()->get();
        return view('clints.wishlist', compact('wishlistItems'));
    }

    public function toggle(Request $request)
    {
        $productId = $request->product_id;
        $user = auth()->user();

        $wishlistItem = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($wishlistItem) {
            $wishlistItem->delete();
            $status = 'removed';
            $message = 'تمت إزالة المنتج من قائمة الأمنيات';
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $productId
            ]);
            $status = 'added';
            $message = 'تم إضافة المنتج إلى قائمة الأمنيات';
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'count' => $user->wishlist()->count()
        ]);
    }

    public function destroy($id)
    {
        $wishlistItem = auth()->user()->wishlist()->findOrFail($id);
        $wishlistItem->delete();

        return back()->with('success', 'تمت إزالة المنتج من قائمة الأمنيات');
    }
}
