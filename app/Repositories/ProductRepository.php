<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function getAll(array $filters = [], string $orderBy = 'id', string $orderDir = 'desc')
    {
        $query = $this->model->with(['variants.prices', 'tags']);

        // Apply filters
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (isset($filters['product_type'])) {
            $query->where('product_type', $filters['product_type']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['is_feature'])) {
            $query->where('is_feature', $filters['is_feature']);
        }

        // Filter by tags
        if (isset($filters['tag_id'])) {
            $query->whereHas('tags', function ($q) use ($filters) {
                $q->where('tag_id', $filters['tag_id']);
            });
        }

        return $query->orderBy($orderBy, $orderDir)->paginate(10);
    }

    public function findById($id)
    {
        return $this->model->with(['variants.prices', 'tags'])->findOrFail($id);
    }

    public function create(array $data)
    {
        // Create product
        $product = $this->model->create([
            'product_name' => $data['product_name'],
            'product_type' => $data['product_type'],
            'description' => $data['description'] ?? null,
            'thumb' => $data['thumb'] ?? null,
            'status' => $data['status'] ?? true,
            'is_feature' => $data['is_feature'] ?? false,
        ]);

        // Sync tags if provided
        if (isset($data['tags'])) {
            $product->tags()->sync($data['tags']);
        }

        return $product;
    }

    public function update($id, array $data)
    {
        $product = $this->findById($id);
        
        $product->update([
            'product_name' => $data['product_name'],
            'product_type' => $data['product_type'],
            'description' => $data['description'] ?? null,
            'thumb' => $data['thumb'] ?? $product->thumb,
            'status' => $data['status'] ?? $product->status,
            'is_feature' => $data['is_feature'] ?? $product->is_feature,
        ]);

        // Sync tags if provided
        if (isset($data['tags'])) {
            $product->tags()->sync($data['tags']);
        }

        return $product;
    }

    public function delete($id)
    {
        $product = $this->findById($id);
        return $product->delete();
    }

    public function toggleStatus($id)
    {
        $product = $this->findById($id);
        $product->status = !$product->status;
        $product->save();
        return $product;
    }

    public function toggleFeature($id)
    {
        $product = $this->findById($id);
        $product->is_feature = !$product->is_feature;
        $product->save();
        return $product;
    }

    public function getProductsWithVariants(array $filters = [])
    {
        $query = $this->model->with(['variants.prices']);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('product_name')->get();
    }

    public function getFeaturedProducts()
    {
        return $this->model->with(['variants.prices'])
            ->where('is_feature', true)
            ->where('status', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}