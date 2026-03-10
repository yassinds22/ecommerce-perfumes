<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Services\CategoryService;
use App\Services\NoteService;
use App\Models\Brand;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use LogsActivity;
    /**
     * @var ProductService
     */
    protected $productService;

    /**
     * @var CategoryService
     */
    protected $categoryService;

    /**
     * @var NoteService // Added
     */
    protected $noteService; // Added

    /**
     * ProductController constructor.
     *
     * @param ProductService $productService
     * @param CategoryService $categoryService
     * @param NoteService $noteService // Added
     */
    public function __construct(ProductService $productService, CategoryService $categoryService, NoteService $noteService) // Modified
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->noteService = $noteService; // Added
    }

    /**
     * Display a listing of the resource.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $products = $this->productService->getPaginatedProducts(12);
        
        if ($request->ajax()) {
            return view('admin.products._table', compact('products'))->render();
        }

        return view('admin.products.index', compact('products'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = $this->categoryService->getAllCategories();
        $brands = Brand::all();
        $notes = $this->noteService->getAllNotes(); // Added
        return view('admin.products.create', compact('categories', 'brands', 'notes')); // Modified
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
            'description.en' => 'nullable|string',
            'description.ar' => 'nullable|string',
            'short_description.en' => 'nullable|string',
            'short_description.ar' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|unique:products,sku',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'gender' => 'required|in:Men,Women,Unisex,Kids',
            'is_featured' => 'nullable|boolean',
            'is_bestseller' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'image' => 'nullable|image|max:2048',
            'gallery.*' => 'nullable|image|max:2048',
            'top_notes' => 'nullable|array', // Added
            'top_notes.*' => 'exists:fragrance_notes,id', // Added
            'middle_notes' => 'nullable|array', // Added
            'middle_notes.*' => 'exists:fragrance_notes,id', // Added
            'base_notes' => 'nullable|array', // Added
            'base_notes.*' => 'exists:fragrance_notes,id', // Added
        ]);


        $product = $this->productService->createProduct($data);

        $this->logActivity('إضافة منتج جديد', "تم إضافة المنتج: {$product->name}", $product);

        return redirect()->route('admin.products.index')->with('success', 'تم إضافة المنتج بنجاح');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $product = $this->productService->getProductById($id);
        $categories = $this->categoryService->getAllCategories();
        $brands = Brand::all();
        $notes = $this->noteService->getAllNotes(); // Added
        
        // Get selected notes for each type // Added
        $selectedTopNotes = $product->topNotes()->pluck('fragrance_notes.id')->toArray(); // Added
        $selectedMiddleNotes = $product->heartNotes()->pluck('fragrance_notes.id')->toArray(); // Added
        $selectedBaseNotes = $product->baseNotes()->pluck('fragrance_notes.id')->toArray(); // Added

        return view('admin.products.edit', compact('product', 'categories', 'brands', 'notes', 'selectedTopNotes', 'selectedMiddleNotes', 'selectedBaseNotes')); // Modified
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
            'description.en' => 'nullable|string',
            'description.ar' => 'nullable|string',
            'short_description.en' => 'nullable|string',
            'short_description.ar' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|unique:products,sku,' . $id,
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'gender' => 'required|in:Men,Women,Unisex,Kids',
            'is_featured' => 'nullable|boolean',
            'is_bestseller' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'image' => 'nullable|image|max:2048',
            'gallery.*' => 'nullable|image|max:2048',
            'top_notes' => 'nullable|array', // Added
            'top_notes.*' => 'exists:fragrance_notes,id', // Added
            'middle_notes' => 'nullable|array', // Added
            'middle_notes.*' => 'exists:fragrance_notes,id', // Added
            'base_notes' => 'nullable|array', // Added
            'base_notes.*' => 'exists:fragrance_notes,id', // Added
        ]);


        $product = $this->productService->updateProduct($id, $data);

        $this->logActivity('تعديل منتج', "تم تعديل بيانات المنتج: {$product->name}", $product);

        return redirect()->route('admin.products.index')->with('success', 'تم تحديث المنتج بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->productService->deleteProduct($id);

        $this->logActivity('حذف منتج', "تم حذف المنتج رقم: {$id}");

        return redirect()->route('admin.products.index')->with('success', 'تم حذف المنتج بنجاح');
    }

    /**
     * Get product media for modal.
     */
    public function getMedia($id)
    {
        if ($id instanceof \App\Models\Product) {
            $product = $id;
        } else {
            $product = $this->productService->getProductById((int)$id);
        }

        $images = [];
        
        // Main image
        if ($product->getFirstMedia('images')) {
            $media = $product->getFirstMedia('images');
            $images[] = [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'collection' => 'images'
            ];
        }
        
        // Gallery images
        foreach ($product->getMedia('gallery') as $media) {
            $images[] = [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'collection' => 'gallery'
            ];
        }
        
        return response()->json(['images' => $images]);
    }

    /**
     * Delete specific media.
     */
    public function deleteMedia($id)
    {
        $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::findOrFail($id);
        $media->delete();
        
        return response()->json(['success' => true]);
    }
}

