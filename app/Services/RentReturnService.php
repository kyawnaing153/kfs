<?php

namespace App\Services;

use App\Repositories\RentReturnRepository;
use App\Repositories\RentItemRepository;
use App\Repositories\ProductVariantRepository;
use App\Repositories\RentRepository;
use Illuminate\Support\Facades\DB;

class RentReturnService
{
    protected $returnRepository;
    protected $rentItemRepository;
    protected $productVariantRepository;
    protected $rentRepository;

    public function __construct(
        RentReturnRepository $returnRepository,
        RentItemRepository $rentItemRepository,
        ProductVariantRepository $productVariantRepository,
        RentRepository $rentRepository
    ) {
        $this->returnRepository = $returnRepository;
        $this->rentItemRepository = $rentItemRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->rentRepository = $rentRepository;
    }

    /**
     * Get all returns
     */
    public function getAllReturns(array $filters = [], string $status = 'all', string $orderBy = 'return_date', string $orderDir = 'desc')
    {
        return $this->returnRepository->getAll($filters, $status, $orderBy, $orderDir);
    }

    /**
     * Create return with stock increment
     */
    public function createReturn($rent, array $data)
    {
        return DB::transaction(function () use ($rent, $data) {
            // Calculate refund and collect amounts
            $refundAmount = 0;
            $collectAmount = 0;
            
            foreach ($data['items'] as &$item) {
                // Update returned quantity
                $this->rentItemRepository->updateReturnedQuantity(
                    $item['rent_item_id'],
                    $item['qty']
                );
                
                // Increase stock
                $rentItem = $this->rentItemRepository->find($item['rent_item_id']);
                $this->productVariantRepository->increaseStock(
                    $rentItem->product_variant_id,
                    $item['qty']
                );
            }
            
            // Set return status
            $returnStatus = $this->areAllItemsReturned($rent) ? 'completed' : 'partial';
            
            // Create return record
            $returnData = [
                'refund_amount' => $data['refund_amount'] ?? 0,
                'collect_amount' => $data['collect_amount'] ?? 0,
                'return_date' => $data['return_date'],
                'total_days' => $data['total_days'],
                'transport' => $data['transport'] ?? null,
                'return_image' => $data['return_image'] ?? null,
                'status' => $returnStatus,
                'note' => $data['note'] ?? null
            ];
            
            $return = $this->returnRepository->createWithItems(
                $rent->id,
                $returnData,
                $data['items']
            );
            
            // Update rent totals and status
            $this->updateRentAfterReturn($rent, $collectAmount, $refundAmount);
            
            return $return;
        });
    }

    /**
     * Calculate late fee for returned items
     */
    private function calculateLateFee($rent, int $rentItemId, int $returnedQty): float
    {
        // Implement your late fee calculation logic here
        // This could be based on overdue days, contract terms, etc.
        return 0; // Default no late fee
    }

    /**
     * Check if all items are returned
     */
    private function areAllItemsReturned($rent): bool
    {
        return $this->rentItemRepository->areAllItemsReturned($rent->id);
    }

    /**
     * Update rent after return
     */
    private function updateRentAfterReturn($rent, float $collectAmount, float $refundAmount): void
    {
        // Update rent status
        if ($this->areAllItemsReturned($rent)) {
            $rent->update(['status' => 'completed']);
        } else {
            $rent->update(['status' => 'ongoing']);
        }
    }

    /**
     * Get remaining items to return
     */
    public function getRemainingItems($rent)
    {
        return $this->rentItemRepository->getItemsWithRemainingQuantity($rent->id);
    }
}