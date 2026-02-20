<?php

namespace App\Repositories\Interfaces;

interface SaleRepositoryInterface
{
    public function getAllSales(array $filters = []);
    public function getSaleById(string $id);
    public function createSale(array $data);
    public function updateSale(string $id, array $data);
    public function deleteSale(string $id);
    public function getSalesByStatus(string $status);
    public function getSalesByCustomer(string $customerId);
    public function getSalesByDateRange(string $startDate, string $endDate);
    public function generateSaleCode(): string;
    public function getSaleStatictics($sales);
}