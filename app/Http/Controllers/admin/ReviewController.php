<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * @var ReviewService
     */
    protected $reviewService;

    /**
     * ReviewController constructor.
     *
     * @param ReviewService $reviewService
     */
    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function index(Request $request)
    {
        $reviews = $this->reviewService->getReviews($request->all(), 10);
        
        if ($request->ajax()) {
            return view('admin.sections.reviews', compact('reviews'))->render();
        }
        
        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve($id)
    {
        $this->reviewService->approveReview($id);
        return response()->json(['success' => true, 'message' => 'تم اعتماد التقييم بنجاح']);
    }

    public function reject($id)
    {
        $this->reviewService->rejectReview($id);
        return response()->json(['success' => true, 'message' => 'تم إلغاء اعتماد التقييم']);
    }

    public function toggleVerified($id)
    {
        $this->reviewService->toggleVerified($id);
        return response()->json(['success' => true, 'message' => 'تم تحديث شارة التوثيق']);
    }

    public function toggleVisibility($id)
    {
        $this->reviewService->toggleVisibility($id);
        return response()->json(['success' => true, 'message' => 'تم تحديث حالة التقييم بنجاح']);
    }

    public function destroy($id)
    {
        $this->reviewService->deleteReview($id);
        return response()->json(['success' => true, 'message' => 'تم حذف التقييم بنجاح']);
    }
}
