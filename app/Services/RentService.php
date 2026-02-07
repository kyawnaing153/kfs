<?php

namespace App\Services;

use App\Repositories\Interfaces\{
    RentRepositoryInterface,
    RentItemRepositoryInterface,
    ProductVariantRepositoryInterface
};
use Illuminate\Support\Facades\DB;

class RentService
{
    protected $rentRepository;
    protected $rentItemRepository;
    protected $productVariantRepository;

    public function __construct(
        RentRepositoryInterface $rentRepository,
        RentItemRepositoryInterface $rentItemRepository,
        ProductVariantRepositoryInterface $productVariantRepository
    ) {
        $this->rentRepository = $rentRepository;
        $this->rentItemRepository = $rentItemRepository;
        $this->productVariantRepository = $productVariantRepository;
    }

    /**
     * Create new rent with stock decrement
     */
    public function createRent(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Generate rent code
            $data['rent_code'] = $this->rentRepository->generateRentCode();

            // Calculate totals WITHOUT reference
            $subTotal = 0;
            foreach ($data['items'] as $key => $item) {
                $total = $item['rent_qty'] * $item['unit_price'];
                $data['items'][$key]['total'] = $total;
                $subTotal += $total;
            }

            $data['sub_total'] = $subTotal;
            $data['total'] = ($data['transport'] ?? 0) + ($data['deposit'] ?? 0);
            $data['total_paid'] = $data['total_paid'] ?? 0;
            $data['total_due'] = $data['total_due'] ?? 0;

            // Create rent
            $rent = $this->rentRepository->createWithItems($data, $data['items']);

            // Decrease stock for each item — now safe!
            foreach ($data['items'] as $item) {
                $this->productVariantRepository->decreaseStock(
                    $item['product_variant_id'],
                    $item['rent_qty']
                );
            }

            return $rent;
        });
    }

    /**
     * Update existing rent
     */
    public function updateRent($rent, array $data)
    {
        if ($rent->status !== 'pending') {
            throw new \Exception('Only pending rents can be updated.');
        }

        return DB::transaction(function () use ($rent, $data) {
            // Return stock from old items
            foreach ($rent->items as $item) {
                $this->productVariantRepository->increaseStock(
                    $item->product_variant_id,
                    $item->rent_qty
                );
            }

            // Decrease stock for new items
            foreach ($data['items'] as $item) {
                $this->productVariantRepository->decreaseStock(
                    $item['product_variant_id'],
                    $item['rent_qty']
                );
            }

            // Recalculate totals
            $subTotal = 0;
            foreach ($data['items'] as &$item) {
                $item['total'] = $item['rent_qty'] * $item['unit_price'];
                $subTotal += $item['total'];
            }

            $data['sub_total'] = $subTotal;
            $data['total'] = ($data['transport'] ?? 0) + ($data['deposit'] ?? 0);
            $data['total_paid'] = $data['total_paid'] ?? 0;
            $data['total_due'] = $data['total_due'] ?? 0;



            // Update rent with new items
            $rent = $this->rentRepository->updateWithItems($rent, $data, $data['items']);

            return $rent;
        });
    }

    /**
     * Cancel rent and restore stock
     */
    public function cancelRent($rent)
    {
        if ($rent->status === 'completed') {
            throw new \Exception('Completed rents cannot be cancelled.');
        }

        return DB::transaction(function () use ($rent) {
            // Return stock
            foreach ($rent->items as $item) {
                $remainingQty = $item->rent_qty - $item->returned_qty;
                if ($remainingQty > 0) {
                    $this->productVariantRepository->increaseStock(
                        $item->product_variant_id,
                        $remainingQty
                    );
                }
            }

            // Update rent status
            $rent->update(['status' => 'cancelled']);

            return $rent;
        });
    }

    /**
     * Update rent status based on returns
     */
    public function updateRentStatus($rent)
    {
        if ($this->rentItemRepository->areAllItemsReturned($rent->id)) {
            $rent->update(['status' => 'completed']);
        } elseif ($rent->items()->where('returned_qty', '>', 0)->exists()) {
            $rent->update(['status' => 'ongoing']);
        }
    }

    /**
     * Update rent totals after payment
     */
    public function updateRentTotals($rent, float $paymentAmount)
    {
        $newTotalPaid = $rent->total_paid + $paymentAmount;
        $newTotalDue = max(0, $rent->total - $newTotalPaid);

        $rent->update([
            'total_paid' => $newTotalPaid,
            'total_due' => $newTotalDue
        ]);
    }

    /**
     * Get available customers
     */
    public function getAvailableCustomers()
    {
        return \App\Models\Customer::active()->get();
    }

    /**
     * Get available product variants with stock
     */
    public function getAvailableProductVariants()
    {
        return \App\Models\ProductVariant::with('product', 'prices')
            ->where('qty', '>', 0)
            ->get();
    }

    /**
     * Get rents by status
     */
    public function getRents(string $status = 'all')
    {
        return $this->rentRepository->getByStatus($status);
    }

    /**
     * Get rent items 
     */
    public function getRentItems(array $filters = [])
    {
        return $this->rentItemRepository->getAllRentItems($filters);
    }
}
