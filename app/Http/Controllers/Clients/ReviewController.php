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
            'comment' => 'required|string|max:1000',
        ]);

        $review = Review::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => false, // Default to false for moderation
        ]);

        // Note: NewReviewNotification is handled here if needed, 
        // but for now we focus on the core requirement.

        return response()->json([
            'success' => true,
            'message' => 'شكراً لتقييمك! سيظهر تقييمك بعد مراجعة الإدارة.'
        ]);
    }
}
