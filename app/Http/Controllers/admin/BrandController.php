<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BrandService;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * @var BrandService
     */
    protected $brandService;

    /**
     * BrandController constructor.
     *
     * @param BrandService $brandService
     */
    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = $this->brandService->getAllBrands();
        return view('admin.brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'logo' => 'nullable|image|max:2048',
        ]);

        $this->brandService->createBrand($data);

        return redirect()->route('admin.brands.index')->with('success', 'تم إضافة الماركة بنجاح');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $brand = $this->brandService->getBrandById($id);
        return view('admin.brands.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $id,
            'logo' => 'nullable|image|max:2048',
        ]);

        $this->brandService->updateBrand($id, $data);

        return redirect()->route('admin.brands.index')->with('success', 'تم تحديث الماركة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->brandService->deleteBrand($id);
        return redirect()->route('admin.brands.index')->with('success', 'تم حذف الماركة بنجاح');
    }
}
