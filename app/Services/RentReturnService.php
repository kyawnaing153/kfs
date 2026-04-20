<?php

namespace App\Services;

use App\Repositories\RentReturnRepository;
use App\Repositories\RentItemRepository;
use App\Repositories\ProductVariantRepository;
use App\Repositories\RentRepository;
use Illuminate\Support\Facades\DB;
use App\Mail\RentReturnInvoiceMail;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class RentReturnService
{
    protected $returnRepository;
    protected $rentItemRepository;
    protected $productVariantRepository;
    protected $rentRepository;
    protected $rentPaymentService;

    public function __construct(
        RentReturnRepository $returnRepository,
        RentItemRepository $rentItemRepository,
        ProductVariantRepository $productVariantRepository,
        RentRepository $rentRepository,
        RentPaymentService $rentPaymentService
    ) {
        $this->returnRepository = $returnRepository;
        $this->rentItemRepository = $rentItemRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->rentRepository = $rentRepository;
        $this->rentPaymentService = $rentPaymentService;
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

    /**
     * Send return invoice email
     */
    public function sendReturnInvoiceEmail($rent, $return)
    {
        $customerEmail = $rent->customer->email;

        if (!$customerEmail) {
            return;
        }

        // Prepare the return data with all calculations (like in print method)
        $return = $this->prepareReturnInvoiceData($rent, $return);

        // Generate PDF content for attachment
        $pdfContent = $this->getReturnInvoicePdf($rent, $return);

        // Send email with PDF attachment
        Mail::to($customerEmail)
            ->cc(config('mail.from.address')) // CC to admin
            ->send(new RentReturnInvoiceMail($rent, $return, $pdfContent));
    }

    /**
     * Prepare return invoice data (mirroring your print method logic)
     */
    private function prepareReturnInvoiceData($rent, $return)
    {
        // Load relationships
        $return->load([
            'items.rentItem.productVariant.product',
            'rent.customer',
            'rent.payments'
        ]);

        // Get total payment by rent ID
        $totalPaymentByRentId = $this->rentPaymentService->getTotalPaymentByRentId($rent->id);
        $return->total_payments = $totalPaymentByRentId;

        // Calculate total rental amount
        $return->total_rental_amount = $return->rent->sub_total * $return->total_days;

        // Set current time
        $return->current_time = now()->format('Y-m-d H:i:s');

        // Calculate item totals
        foreach ($return->items as $item) {
            $item->damage_total = $item->damage_fee ?? 0;
            $item->returned_total = $item->qty * ($item->rentItem->unit_price ?? 0);
        }

        // Calculate total damage fee
        $return->total_damage_fee = $return->items->sum('damage_fee');

        return $return;
    }

    /**
     * Generate PDF content for email attachment
     */
    public function getReturnInvoicePdf($rent, $return)
    {
        // Get total payment by rent ID
        $totalPaymentByRentId = $this->rentPaymentService->getTotalPaymentByRentId($rent->id);

        // Generate PDF using your existing invoice blade view
        $pdf = Pdf::loadView('pages.admin.pdf.rent-return-invoice', compact('rent', 'return', 'totalPaymentByRentId'));

        return $pdf->output();
    }
}
