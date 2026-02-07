<?php

namespace App\Repositories\Interfaces;

use App\Models\Product;
use App\Models\ProductVariant;

interface ProductVariantRepositoryInterface
{
    public function getProductWithVariants($productId);
    public function createVariant($productId, array $data);
    public function updateVariant($variantId, array $data);
    public function deleteVariant($variantId);
    public function getVariant($variantId);
    public function updateStock($variantId, $quantity);
    public function decreaseStock($variantId, $quantity);
    public function increaseStock($variantId, $quantity);
    public function getVariantPrices($variantId);
    public function updateOrCreatePrice($variantId, array $priceData);
    public function deletePrice($priceId);
}