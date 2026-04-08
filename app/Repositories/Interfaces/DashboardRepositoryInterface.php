<?php

namespace App\Repositories\Interfaces;

interface DashboardRepositoryInterface
{
    public function getTotalSales(): float;
    public function getTotalExpenses(): float;
    public function getTotalRents(): float;
    public function getTotalCustomers(): int;
    public function getTotalProducts(): int;
    public function getRecentSales(int $limit = 5): array;
    public function getRecentPurchases(int $limit = 5): array;
    public function getRecentRents(int $limit = 5): array;
    public function getRecentExpenses(int $limit = 5): array;
    public function getMonthlySalesData(int $year): array;
    public function getMonthlyRentsData(): array;
    public function getMonthlyExpensesData(int $year): array;
    public function getPaymentStatusSummary(): array;
    public function getTopProducts(int $limit = 5): array;
    public function getLowStockProducts(int $limit = 5): array;
    public function getRentOutstandingPayments(): float;
    public function getSaleOutstandingPayments(): float;
}