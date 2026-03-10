<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CouponService;
use App\Models\Coupon;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    use LogsActivity;
    /**
     * @var CouponService
     */
    protected $couponService;

    /**
     * CouponController constructor.
     *
     * @param CouponService $couponService
     */
    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = $this->couponService->getAllCoupons();
        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = \App\Models\Product::all();
        return view('admin.coupons.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'usage_limit' => 'nullable|integer|min:1',
            'is_global' => 'required|boolean',
            'products' => 'required_if:is_global,0|array',
            'products.*' => 'exists:products,id',
        ]);

        $data['is_active'] = $request->has('is_active');
        $productIds = $request->input('products', []);

        $coupon = $this->couponService->createCoupon($data, $productIds);

        $this->logActivity('إضافة كوبون جديد', "تم إضافة الكوبون بنجاح: {$coupon->code}", $coupon);

        return redirect()->route('admin.coupons.index')->with('success', 'تم إضافة الكوبون بنجاح');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $coupon = $this->couponService->getCouponById($id);
        $products = \App\Models\Product::all();
        $selectedProducts = $coupon->products->pluck('id')->toArray();
        return view('admin.coupons.edit', compact('coupon', 'products', 'selectedProducts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:coupons,code,' . $id,
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'usage_limit' => 'nullable|integer|min:1',
            'is_global' => 'required|boolean',
            'products' => 'required_if:is_global,0|array',
            'products.*' => 'exists:products,id',
        ]);

        $data['is_active'] = $request->has('is_active');
        $productIds = $request->input('products', []);

        $coupon = $this->couponService->updateCoupon($id, $data, $productIds);

        $this->logActivity('تعديل كوبون', "تم تعديل الكوبون: {$coupon->code}", $coupon);

        return redirect()->route('admin.coupons.index')->with('success', 'تم تحديث الكوبون بنجاح');
    }



    /**
     * Toggle the active status of the coupon.
     */
    public function toggleStatus(int $id)
    {
        $this->couponService->toggleStatus($id);
        
        $coupon = \App\Models\Coupon::find($id);
        $statusText = $coupon->is_active ? 'تفعيل' : 'تعطيل';
        $this->logActivity('تغيير حالة الكوبون', "تم {$statusText} الكوبون: {$coupon->code}", $coupon);

        return redirect()->back()->with('success', 'تم تغيير حالة الكوبون بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(int $id)
    {
        $this->couponService->deleteCoupon($id);
        
        $this->logActivity('حذف كوبون', "تم حذف الكوبون رقم: {$id}");

        return redirect()->route('admin.coupons.index')->with('success', 'تم حذف الكوبون بنجاح');
    }
}
