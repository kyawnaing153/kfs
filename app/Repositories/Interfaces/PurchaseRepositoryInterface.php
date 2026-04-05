<?php
// app/Repositories/Interfaces/PurchaseRepositoryInterface.php

namespace App\Repositories\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface PurchaseRepositoryInterface
{
    public function getPurchases(array $filters = [], string $status = 'all', int $perPage = 20): LengthAwarePaginator;
    public function getPurchase(int $id);
    public function getPurchaseByCode(string $code);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function updateStatus(int $id, int $status);
    public function updatePaymentStatus(int $id, int $paymentStatus);
    public function getPurchaseStatistics(): array;
}