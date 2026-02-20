<?php

namespace App\Repositories\Interfaces;
use Illuminate\Database\Eloquent\Collection;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;

interface SaleItemRepositoryInterface
{
    public function getItemsBySaleId(string $saleId);
    public function addItemToSale(array $data);
    public function updateItem(string $id, array $data);
    public function deleteItem(string $id);
    public function deleteItemsBySaleId(string $saleId);
    public function getAllSaleItems(): Collection;
}