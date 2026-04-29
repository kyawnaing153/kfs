<?php

namespace App\Http\Controllers\Frontend\products;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = $this->productService->getAllProducts([], 'created_at', 'desc');
        return view('pages.frontend.products.index', compact('products'));
    }

    public function getAvailableVariants()
    {
        try {
            $variants = $this->productService->getAvailableVariants();
            return response()->json($variants);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load product variants',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $product = $this->productService->getProduct($id);
        if (!$product) {
            abort(404);
        }
        return view('pages.frontend.products.show', compact('product'));    
    }
}
