<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CouponService;
use App\Http\Requests\Admin\CouponRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    protected $couponService;
    protected $productService;

    public function __construct(CouponService $couponService, ProductService $productService)
    {
        $this->couponService = $couponService;
        $this->productService = $productService;
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
        $products = $this->productService->getAllProducts();
        return view('admin.coupons.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CouponRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');
        $productIds = $request->input('products', []);

        $this->couponService->createCoupon($data, $productIds);

        return redirect()->route('admin.coupons.index')->with('success', 'تم إضافة الكوبون بنجاح');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $coupon = $this->couponService->getCouponById($id);
        $products = $this->productService->getAllProducts();
        $selectedProducts = $coupon->products->pluck('id')->toArray();
        return view('admin.coupons.edit', compact('coupon', 'products', 'selectedProducts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CouponRequest $request, int $id)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');
        $productIds = $request->input('products', []);

        $this->couponService->updateCoupon($id, $data, $productIds);

        return redirect()->route('admin.coupons.index')->with('success', 'تم تحديث الكوبون بنجاح');
    }

    /**
     * Toggle the active status of the coupon.
     */
    public function toggleStatus(int $id)
    {
        $this->couponService->toggleStatus($id);
        return redirect()->back()->with('success', 'تم تغيير حالة الكوبون بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->couponService->deleteCoupon($id);
        return redirect()->route('admin.coupons.index')->with('success', 'تم حذف الكوبون بنجاح');
    }
}
