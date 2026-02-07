<?php

namespace App\Repositories\Interfaces;

use App\Models\Product;

interface ProductRepositoryInterface
{
    public function getAll(array $filters = [], string $orderBy = 'id', string $orderDir = 'desc');
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function toggleStatus($id);
    public function toggleFeature($id);
    public function getProductsWithVariants(array $filters = []);
    public function getFeaturedProducts();
}