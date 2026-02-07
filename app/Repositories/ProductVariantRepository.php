<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\VariantPrice;
use App\Repositories\Interfaces\ProductVariantRepositoryInterface;

class ProductVariantRepository implements ProductVariantRepositoryInterface
{
    protected $productModel;
    protected $variantModel;
    protected $priceModel;

    public function __construct(Product $product, ProductVariant $variant, VariantPrice $price)
    {
        $this->productModel = $product;
        $this->variantModel = $variant;
        $this->priceModel = $price;
    }

    public function getProductWithVariants($productId)
    {
        return $this->productModel->with(['variants.prices'])->findOrFail($productId);
    }

    public function createVariant($productId, array $data)
    {
        $variant = $this->variantModel->create([
            'product_id' => $productId,
            'size' => $data['size'] ?? null,
            'unit' => $data['unit'] ?? null,
            'qty' => $data['qty'] ?? 0,
            'purchase_price' => $data['purchase_price'] ?? 0,
            'sku' => $data['sku'],
        ]);

        // Create prices if provided
        if (isset($data['prices'])) {
            foreach ($data['prices'] as $priceData) {
                $this->createPrice($variant->id, $priceData);
            }
        }

        return $variant->load('prices');
    }

    public function updateVariant($variantId, array $data)
    {
        $variant = $this->variantModel->findOrFail($variantId);
        
        $variant->update([
            'size' => $data['size'] ?? $variant->size,
            'unit' => $data['unit'] ?? $variant->unit,
            'qty' => $data['qty'] ?? $variant->qty,
            'purchase_price' => $data['purchase_price'] ?? $variant->purchase_price,
            'sku' => $data['sku'] ?? $variant->sku,
        ]);

        return $variant;
    }

    public function deleteVariant($variantId)
    {
        $variant = $this->variantModel->findOrFail($variantId);
        return $variant->delete();
    }

    public function getVariant($variantId)
    {
        return $this->variantModel->with('prices')->findOrFail($variantId);
    }

    public function updateStock($variantId, $quantity)
    {
        $variant = $this->variantModel->findOrFail($variantId);
        $variant->qty = $quantity;
        $variant->save();
        return $variant;
    }

    public function getVariantPrices($variantId)
    {
        return $this->priceModel->where('product_variant_id', $variantId)->get();
    }

    public function updateOrCreatePrice($variantId, array $priceData)
    {
        return $this->priceModel->updateOrCreate(
            [
                'product_variant_id' => $variantId,
                'price_type' => $priceData['price_type'],
                'duration_days' => $priceData['price_type'] === 'sale' ? null : $priceData['duration_days'],
            ],
            [
                'price' => $priceData['price'],
            ]
        );
    }

    public function deletePrice($priceId)
    {
        $price = $this->priceModel->findOrFail($priceId);
        return $price->delete();
    }

    private function createPrice($variantId, array $priceData)
    {
        return $this->priceModel->create([
            'product_variant_id' => $variantId,
            'price_type' => $priceData['price_type'],
            'duration_days' => $priceData['price_type'] === 'sale' ? null : $priceData['duration_days'],
            'price' => $priceData['price'],
        ]);
    }

    public function decreaseStock($variantId, $quantity)
    {
        $variant = $this->variantModel->findOrFail($variantId);
        $variant->qty = max(0, $variant->qty - $quantity);
        $variant->save();
        return $variant;
    }

    public function increaseStock($variantId, $quantity)
    {
        $variant = $this->variantModel->findOrFail($variantId);
        $variant->qty += $quantity;
        $variant->save();
        return $variant;
    }
}