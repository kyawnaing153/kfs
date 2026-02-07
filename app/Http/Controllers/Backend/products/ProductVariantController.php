<?php

namespace App\Http\Controllers\Backend\products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Product\ProductVariantRequest;
use App\Http\Requests\Backend\Product\VariantPriceRequest;
use App\Services\ProductVariantService;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    protected $variantService;

    public function __construct(ProductVariantService $variantService)
    {
        $this->variantService = $variantService;
    }

    public function manage($productId)
    {
        $product = $this->variantService->getProductWithVariants($productId);
        return view('pages.admin.products.variants.manage', compact('product'));
    }

    public function store(ProductVariantRequest $request, $productId)
    {
        try {
            $data = $request->validated();

            // Handle prices array
            $prices = [];
            if ($request->has('price_type') && $request->has('price')) {
                foreach ($request->price_type as $index => $priceType) {
                    if (!empty($request->price[$index])) {
                        $prices[] = [
                            'price_type' => $priceType,
                            'duration_days' => $priceType === 'rent' ? $request->duration_days[$index] : null,
                            'price' => $request->price[$index],
                        ];
                    }
                }
            }

            $data['prices'] = $prices;

            $variant = $this->variantService->createVariant($productId, $data);

            return redirect()->route('products.variants.manage', $productId)
                ->with('success', 'Variant created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error creating variant: ' . $e->getMessage());
        }
    }

    public function update(ProductVariantRequest $request, $productId, $variantId)
    {
        try {
            $data = $request->validated();

            // Verify variant belongs to product
            $variant = $this->variantService->getVariant($variantId);
            if ($variant->product_id != $productId) {
                return back()->with('error', 'Variant not found for this product');
            }

            $this->variantService->updateVariant($variantId, $data);

            return back()->with('success', 'Variant updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating variant: ' . $e->getMessage());
        }
    }

    public function destroy($productId, $variantId)
    {
        try {
            $this->variantService->deleteVariant($variantId);

            return redirect()->route('products.variants.manage', $productId)
                ->with('success', 'Variant deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting variant: ' . $e->getMessage());
        }
    }

    public function updateStock(Request $request, $productId, $variantId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        try {
            $this->variantService->updateStock($variantId, $request->quantity);

            return back()->with('success', 'Stock updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating stock: ' . $e->getMessage());
        }
    }

    public function storePrice(VariantPriceRequest $request, $productId, $variantId)
    {
        try {
            $data = $request->validated();
            $this->variantService->updateOrCreatePrice($variantId, $data);

            return response()->json([
                'success' => true,
                'message' => 'Price saved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error saving price: ' . $e->getMessage()
            ], 400);
        }
    }

    public function destroyPrice($priceId)
    {
        try {
            $this->variantService->deletePrice($priceId);

            return back()->with('success', 'Price deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting price: ' . $e->getMessage());
        }
    }

    public function getVariantDetails($productId, $variantId)
    {
        try {
            $variant = $this->variantService->getVariant($variantId);
            return response()->json($variant);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Variant not found'], 404);
        }
    }
}
