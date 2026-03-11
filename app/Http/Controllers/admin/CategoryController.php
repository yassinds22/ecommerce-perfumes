<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use LogsActivity;
    /**
     * @var CategoryService
     */
    protected $categoryService;

    /**
     * CategoryController constructor.
     *
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->categoryService->getAllCategories();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentCategories = $this->categoryService->getRootCategories();
        return view('admin.categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(\App\Http\Requests\Admin\CategoryRequest $request)
    {
        $category = $this->categoryService->createCategory($request->validated());

        $this->logActivity('إضافة قسم جديد', "تم إضافة القسم: {$category->name}", $category);


        return redirect()->route('admin.categories.index')->with('success', 'تم إضافة القسم بنجاح');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $category = $this->categoryService->getCategoryById($id);
        $parentCategories = $this->categoryService->getRootCategories();
        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(\App\Http\Requests\Admin\CategoryRequest $request, int $id)
    {
        $category = $this->categoryService->updateCategory($id, $request->validated());

        $this->logActivity('تعديل قسم', "تم تعديل بيانات القسم: {$category->name}", $category);


        return redirect()->route('admin.categories.index')->with('success', 'تم تحديث القسم بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->categoryService->deleteCategory($id);

        $this->logActivity('حذف قسم', "تم حذف القسم رقم: {$id}");
        return redirect()->route('admin.categories.index')->with('success', 'تم حذف القسم بنجاح');
    }
}
