<?php

namespace App\Http\Controllers\Backend\products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Product\ProductRequest;
use App\Services\ProductService;
use App\Models\Tag;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $filters = [];

        if ($request->filled('search')) {
            $filters['search'] = $request->search;
        }

        if ($request->filled('product_type')) {
            $filters['product_type'] = $request->product_type;
        }

        if ($request->filled('status')) {
            $filters['status'] = $request->status;
        }

        if ($request->filled('is_feature')) {
            $filters['is_feature'] = $request->is_feature;
        }

        if ($request->filled('tag_id')) {
            $filters['tag_id'] = $request->tag_id;
        }

        $orderBy = $request->input('order_by', 'id');
        $orderDir = $request->input('order_dir', 'desc');

        $products = $this->productService->getAllProducts($filters, $orderBy, $orderDir);
        $tags = Tag::orderBy('name')->get();

        return view('pages.admin.products.index', compact('products', 'tags'));
    }

    public function create()
    {
        $tags = Tag::orderBy('name')->get();
        $productTypes = ['sale', 'rent', 'both'];
        
        return view('pages.admin.products.create', compact('tags', 'productTypes'));
    }

    public function store(ProductRequest $request)
    {
        try {
            $data = $request->validated();
            
            $this->productService->createProduct(
                $data,
                $request->file('thumb')
            );

            return redirect()->route('products.index')
                ->with('success', 'Product created successfully.');
                
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error creating product: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $product = $this->productService->getProduct($id);
        return view('pages.admin.products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = $this->productService->getProduct($id);
        $tags = Tag::orderBy('name')->get();
        $productTypes = ['sale', 'rent', 'both'];
        
        return view('pages.admin.products.edit', compact('product', 'tags', 'productTypes'));
    }

    public function update(ProductRequest $request, $id)
    {
        try {
            $data = $request->validated();
            
            $this->productService->updateProduct(
                $id,
                $data,
                $request->file('thumb')
            );

            return redirect()->route('products.index')
                ->with('success', 'Product updated successfully.');
                
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating product: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->productService->deleteProduct($id);
            
            return redirect()->route('products.index')
                ->with('success', 'Product deleted successfully.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $product = $this->productService->toggleStatus($id);
            $status = $product->status ? 'activated' : 'deactivated';
            
            return back()->with('success', "Product {$status} successfully.");
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating product status.');
        }
    }

    public function toggleFeature($id)
    {
        try {
            $product = $this->productService->toggleFeature($id);
            $featureStatus = $product->is_feature ? 'featured' : 'unfeatured';
            
            return back()->with('success', "Product {$featureStatus} successfully.");
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating product feature status.');
        }
    }
}