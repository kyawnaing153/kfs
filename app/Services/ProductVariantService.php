<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductVariantRepositoryInterface;

class ProductVariantService
{
    protected $variantRepository;

    public function __construct(ProductVariantRepositoryInterface $variantRepository)
    {
        $this->variantRepository = $variantRepository;
    }

    public function getProductWithVariants($productId)
    {
        return $this->variantRepository->getProductWithVariants($productId);
    }

    public function createVariant($productId, array $data)
    {
        // Validate SKU uniqueness
        if (!$this->validateSku($data['sku'])) {
            throw new \Exception('SKU must be unique');
        }

        return $this->variantRepository->createVariant($productId, $data);
    }

    public function updateVariant($variantId, array $data)
    {
        $variant = $this->variantRepository->getVariant($variantId);
        
        // Check if SKU is being changed and validate uniqueness
        // if (isset($data['sku']) && $data['sku'] !== $variant->sku) {
        //     if (!$this->validateSku($data['sku'])) {
        //         throw new \Exception('SKU must be unique');
        //     }
        // }

        return $this->variantRepository->updateVariant($variantId, $data);
    }

    public function deleteVariant($variantId)
    {
        return $this->variantRepository->deleteVariant($variantId);
    }

    public function getVariant($variantId)
    {
        return $this->variantRepository->getVariant($variantId);
    }

    public function updateStock($variantId, $quantity)
    {
        if ($quantity < 0) {
            throw new \Exception('Quantity cannot be negative');
        }

        return $this->variantRepository->updateStock($variantId, $quantity);
    }

    public function updateOrCreatePrice($variantId, array $priceData)
    {
        // Validate price
        if ($priceData['price'] <= 0) {
            throw new \Exception('Price must be greater than 0');
        }

        // Validate duration for rent prices
        if ($priceData['price_type'] === 'rent' && empty($priceData['duration_days'])) {
            throw new \Exception('Duration days is required for rental prices');
        }

        return $this->variantRepository->updateOrCreatePrice($variantId, $priceData);
    }

    public function deletePrice($priceId)
    {
        return $this->variantRepository->deletePrice($priceId);
    }

    public function getVariantPrices($variantId)
    {
        return $this->variantRepository->getVariantPrices($variantId);
    }

    private function validateSku($sku)
    {
        // Check if SKU exists (excluding current variant if updating)
        $exists = \App\Models\ProductVariant::where('sku', $sku)->exists();
        return !$exists;
    }
}