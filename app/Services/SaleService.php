<?php

namespace App\Services;

use App\Repositories\SaleRepository;
use App\Repositories\{SaleItemRepository, ProductVariantRepository};
use App\Models\{Customer, ProductVariant};
use Illuminate\Support\Str;
use App\Services\SalePdfService;
use App\Mail\SaleInvoiceMail;
use Illuminate\Support\Facades\Mail;

class SaleService
{
    protected $saleRepository;
    protected $saleItemRepository;
    protected $productVariantRepository;
    protected $salePdfService;

    public function __construct(
        SaleRepository $saleRepository,
        SaleItemRepository $saleItemRepository,
        ProductVariantRepository $productVariantRepository,
        SalePdfService $salePdfService
    ) {
        $this->saleRepository = $saleRepository;
        $this->saleItemRepository = $saleItemRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->salePdfService = $salePdfService;
    }

    public function getAllSales(array $filters = [])
    {
        return $this->saleRepository->getAllSales($filters);
    }

    public function getSaleById(string $saleId)
    {
        return $this->saleRepository->getSaleById($saleId);
    }

    public function calculateTotals(array $items): array
    {
        $subTotal = 0;
        $itemCalculations = [];

        foreach ($items as $item) {
            $quantity = $item['sale_qty'] ?? 1;
            $unitPrice = $item['unit_price'] ?? 0;
            $discount = $item['discount'] ?? 0;

            $itemTotal = ($quantity * $unitPrice) - $discount;
            $subTotal += $itemTotal;

            $itemCalculations[] = [
                ...$item,
                'total' => $itemTotal
            ];
        }

        return [
            'items' => $itemCalculations,
            'sub_total' => $subTotal
        ];
    }

    public function createSaleWithItems(array $saleData, array $itemsData): array
    {
        $saleCode = $this->saleRepository->generateSaleCode();
        $calculations = $this->calculateTotals($itemsData);

        $saleData['sale_code'] = $saleCode;
        $saleData['sub_total'] = $calculations['sub_total'];

        // Calculate total with transport and discount
        $total = $calculations['sub_total']
            - ($saleData['discount'] ?? 0)
            + ($saleData['transport'] ?? 0);

        $saleData['total'] = $total;
        $saleData['total_due'] = $total - ($saleData['total_paid'] ?? 0);

        // Create sale
        $sale = $this->saleRepository->createSale($saleData);

        // Create sale items
        foreach ($calculations['items'] as $item) {
            $item['sale_id'] = $sale->id;
            $this->saleItemRepository->addItemToSale($item);

            // Decrease stock for each item
            $this->productVariantRepository->decreaseStock(
                $item['product_variant_id'],
                $item['sale_qty']
            );
        }

        return [
            'sale' => $sale,
            'items' => $calculations['items']
        ];
    }

    public function updateSaleWithItems(string $saleId, array $saleData, array $itemsData): bool
    {
        $calculations = $this->calculateTotals($itemsData);

        $saleData['sub_total'] = $calculations['sub_total'];

        // Calculate total with transport and discount
        $total = $calculations['sub_total']
            - ($saleData['discount'] ?? 0)
            + ($saleData['transport'] ?? 0);

        $saleData['total'] = $total;
        $saleData['total_due'] = $total - ($saleData['total_paid'] ?? 0);

        // Update sale
        $updated = $this->saleRepository->updateSale($saleId, $saleData);

        if ($updated) {
            // Delete existing items and add new ones
            $this->saleItemRepository->deleteItemsBySaleId($saleId);

            foreach ($calculations['items'] as $item) {
                $item['sale_id'] = $saleId;
                $this->saleItemRepository->addItemToSale($item);
            }
        }

        return $updated;
    }

    public function updateSale($saleId, $saleData, array $itemData)
    {
        // Fetch the Sale model to access items
        $sale = $this->saleRepository->getSaleById($saleId);

        // Sale Return stock from old items
        foreach ($sale->items as $item) {
            $this->productVariantRepository->increaseStock(
                $item['product_variant_id'],
                $item['sale_qty']
            );
        }

        // Decrease stock for new items
        foreach ($itemData as $item) {
            $this->productVariantRepository->decreaseStock(
                $item['product_variant_id'],
                $item['sale_qty']
            );
        }

        // Update sale
        $updated = $this->saleRepository->updateSale($saleId, $saleData);

        if ($updated) {
            // Delete existing items and add new ones
            $this->saleItemRepository->deleteItemsBySaleId($saleId);

            foreach ($itemData as $item) {
                $item['sale_id'] = $saleId;
                $this->saleItemRepository->addItemToSale($item);
            }
        }
        return $updated;
    }

    public function markAsCompleted(string $saleId): bool
    {
        $sale = $this->saleRepository->getSaleById($saleId);

        if ($sale && $sale->total_due <= 0) {
            return $this->saleRepository->updateSale($saleId, ['status' => 'completed']);
        }

        return false;
    }

    public function deleteSale(string $saleId)
    {
        return $this->saleRepository->deleteSale($saleId);
    }

    public function getAvailableCustomers()
    {
        return Customer::active()->get();
    }

    public function getAvailableProductVariants()
    {
        return ProductVariant::with('product', 'prices')->where('qty', '>', 0)->get();
    }

    public function getSaleItems(array $filters = [])
    {
        return $this->saleItemRepository->getAllSaleItems($filters);
    }

    public function getSaleStatictics($sales)
    {
        return $this->saleRepository->getSaleStatictics($sales);
    }

    public function sendSaleInvoiceEmail($sale)
    {
        try{
            $customerEmail = $sale->customer->email;

            if(!$customerEmail){
                return;
            }

            //Generate PDF invoice
            $pdf = $this->salePdfService->getSaleInvoicePdf($sale);
            $pdfContent = $pdf;

            //Send email with PDF attachment
            Mail::to($customerEmail)
                ->cc(config('mail.from.address'))
                ->send(new SaleInvoiceMail($sale, $pdfContent));

        } catch (\Exception $e) {
            throw new \Exception('Failed to send invoice email: ' . $e->getMessage());
        }
    }
}
