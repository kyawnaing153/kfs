<?php

namespace App\Services;

use App\Repositories\RentPaymentRepository;
use App\Repositories\RentRepository;
use Illuminate\Support\Facades\DB;

class RentPaymentService
{
    protected $paymentRepository;
    protected $rentRepository;

    public function __construct(
        RentPaymentRepository $paymentRepository,
        RentRepository $rentRepository
    ) {
        $this->paymentRepository = $paymentRepository;
        $this->rentRepository = $rentRepository;
    }

    /**
     * Get payments with filters and sorting
     */
    public function getPayments(array $filters = [], string $orderBy = 'payment_date', string $orderDir = 'desc')
    {
        return $this->paymentRepository->findAll($filters, $orderBy, $orderDir);
    }

    /**
     * Get comprehensive payment statistics
     */
    public function getPaymentStatistics(): array
    {
        return $this->paymentRepository->getStatistics();
    }

    /**
     * Create payment and update rent totals
     */
    public function createPayment($rent, array $data)
    {
        return DB::transaction(function () use ($rent, $data) {
            // Validate payment amount doesn't exceed due
            if ($data['amount'] <= 0) {
                throw new \Exception('Payment amount must be greater than zero.');
            }
            
            // Create payment record
            $payment = $this->paymentRepository->createPayment($rent->id, $data);
            
            if($data['payment_for'] === 'deposit'){
                // Update rent totals
            $this->updateRentTotals($rent, $data['amount']);
            }  
        
            return $payment;
        });
    }

    /**
     * Update rent totals after payment
     */
    private function updateRentTotals($rent, float $paymentAmount): void
    {
        $newDeposit = $rent->deposit + $paymentAmount;
        
        $rent->update([
            'deposit' => $newDeposit,
        ]);
    }

    /**
     * Calculate monthly payment amount
     */
    public function calculateMonthlyPayment($rent): float
    {
        // Implement your monthly payment calculation logic
        // This could be based on contract terms, duration, etc.
        return $rent->total / 3; // Example: split into 3 payments
    }

    /**
     * Get payment summary for rent
     */
    public function getPaymentSummary($rent): array
    {
        $totalPaid = $this->paymentRepository->getTotalPaid($rent->id);
        $lastPayment = $this->paymentRepository->getLastPayment($rent->id);
        
        return [
            'total_paid' => $totalPaid,
            'total_due' => $rent->total - $totalPaid,
            'last_payment' => $lastPayment,
            'payment_count' => $rent->payments()->count(),
            'next_payment_due' => $this->calculateNextPaymentDue($rent, $lastPayment)
        ];
    }

    /**
     * Calculate next payment due date
     */
    private function calculateNextPaymentDue($rent, $lastPayment): ?string
    {
        if (!$lastPayment || $lastPayment->payment_for === 'final') {
            return null;
        }
        
        // Assuming monthly payments
        $nextDate = date('Y-m-d', strtotime($lastPayment->payment_date . ' +1 month'));
        
        return $nextDate;
    }

    public function getTotalPaymentByRentId($rentId): float
    {
        return $this->paymentRepository->getTotalPaymentByRentId($rentId);
    }
}