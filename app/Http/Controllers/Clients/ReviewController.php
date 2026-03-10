<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\User;
use App\Notifications\NewReviewNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        $review = Review::create([
            'user_id' => auth()->id() ?? 1, // Fallback
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'title' => $request->title ?? 'تقييم من العميل',
            'comment' => $request->comment,
            'is_approved' => false, // Needs moderation
        ]);

        // Notify Admins
        $admins = User::where('role', 'Admin')->get();
        Notification::send($admins, new NewReviewNotification($review));

        return response()->json([
            'success' => true,
            'message' => 'شكراً لتقييمك! سيظهر بعد مراجعة المسؤول.'
        ]);
    }
}
