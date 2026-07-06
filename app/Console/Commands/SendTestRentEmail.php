<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Backend\Rent;
use App\Services\RentPdfService;
use App\Mail\RentInvoiceMail;
use Illuminate\Support\Facades\Mail;

class SendTestRentEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test-rent {email=labroom108@gmail.com}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test rent invoice email with a generated PDF invoice attached';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $this->info("Starting mail test to: {$email}");

        $rent = Rent::latest()->first();
        if (!$rent) {
            $this->error("No Rent record found in the database!");
            return Command::FAILURE;
        }

        $this->info("Found Rent ID: {$rent->id} Code: {$rent->rent_code}");
        $this->info("Generating PDF...");

        try {
            $pdfService = app(RentPdfService::class);
            $pdfContent = $pdfService->getRentInvoicePdf($rent);
            $this->info("PDF generated successfully.");

            $this->info("Sending email via SMTP...");
            Mail::to($email)->send(new RentInvoiceMail($rent, $pdfContent));
            $this->info("Email sent successfully!");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Error sending email: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
