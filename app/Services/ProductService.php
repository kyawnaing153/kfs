<?php

namespace App\Services;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts(array $filters = [], string $orderBy = 'id', string $orderDir = 'desc')
    {
        return $this->productRepository->getAll($filters, $orderBy, $orderDir);
    }

    public function getProduct($id)
    {
        return $this->productRepository->findById($id);
    }

    public function createProduct(array $data, ?UploadedFile $thumb = null)
    {
        // Handle thumbnail upload
        if ($thumb) {
            $data['thumb'] = $thumb->store('products/thumbnails', 'public');
        }

        return $this->productRepository->create($data);
    }

    public function updateProduct($id, array $data, ?UploadedFile $thumb = null)
    {
        $product = $this->productRepository->findById($id);

        // Handle thumbnail update
        if ($thumb) {
            // Delete old thumbnail if exists
            if ($product->thumb) {
                Storage::disk('public')->delete($product->thumb);
            }
            $data['thumb'] = $thumb->store('products/thumbnails', 'public');
        }

        return $this->productRepository->update($id, $data);
    }

    public function deleteProduct($id)
    {
        $product = $this->productRepository->findById($id);
        
        // Delete thumbnail if exists
        if ($product->thumb) {
            Storage::disk('public')->delete($product->thumb);
        }

        return $this->productRepository->delete($id);
    }

    public function toggleStatus($id)
    {
        return $this->productRepository->toggleStatus($id);
    }

    public function toggleFeature($id)
    {
        return $this->productRepository->toggleFeature($id);
    }

    public function getProductsWithVariants(array $filters = [])
    {
        return $this->productRepository->getProductsWithVariants($filters);
    }

    public function getFeaturedProducts()
    {
        return $this->productRepository->getFeaturedProducts();
    }
}