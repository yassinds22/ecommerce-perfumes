<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        $query = Review::with(['user', 'product']);

        // Filtering
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        if ($request->filled('status')) {
            if ($request->status === 'approved') $query->where('is_approved', true);
            elseif ($request->status === 'pending') $query->where('is_approved', false);
        }

        $reviews = $query->latest()->paginate(10);
        
        if ($request->ajax()) {
            return view('admin.sections.reviews', compact('reviews'))->render();
        }
        
        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve($id)
    {
        $review = Review::findOrFail($id);
        $review->is_approved = true;
        $review->save();

        $this->logActivity('اعتماد تقييم', "تم اعتماد تقييم المستخدم {$review->user->name} على المنتج {$review->product->name}", $review);

        return response()->json(['success' => true, 'message' => 'تم اعتماد التقييم بنجاح']);
    }

    public function reject($id)
    {
        $review = Review::findOrFail($id);
        $review->is_approved = false;
        $review->save();

        $this->logActivity('إلغاء اعتماد تقييم', "تم إلغاء اعتماد تقييم المستخدم {$review->user->name} على المنتج {$review->product->name}", $review);

        return response()->json(['success' => true, 'message' => 'تم إلغاء اعتماد التقييم']);
    }

    public function toggleVerified($id)
    {
        $review = Review::findOrFail($id);
        $review->is_verified_purchase = !$review->is_verified_purchase;
        $review->save();

        return response()->json(['success' => true, 'message' => 'تم تحديث شارة التوثيق']);
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
