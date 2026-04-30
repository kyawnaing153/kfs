<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProductService;

class HomeController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }


    public function index()
    {
        $products = $this->productService->getAllProducts([], 'id', 'asc');
        $products = $products->take(8);

        return view('pages.frontend.home', compact('products'));
    }

    public function services()
    {
        return view('pages.frontend.services');
    }

    public function contact()
    {
        return view('pages.frontend.contact');
    }
}
