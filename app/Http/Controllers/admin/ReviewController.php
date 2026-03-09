<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::with(['user', 'product'])->latest()->paginate(10);
        
        if ($request->ajax()) {
            return view('admin.sections.reviews', compact('reviews'))->render();
        }
        
        return view('admin.reviews.index', compact('reviews'));
    }

    public function toggleVisibility($id)
    {
        $review = Review::findOrFail($id);
        $review->is_visible = !$review->is_visible;
        $review->save();

        return response()->json(['success' => true, 'message' => 'تم تحديث حالة التقييم بنجاح']);
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return response()->json(['success' => true, 'message' => 'تم حذف التقييم بنجاح']);
    }
}
