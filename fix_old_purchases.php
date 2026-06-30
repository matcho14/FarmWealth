<?php

use App\Models\PurchaseInvoice;
use App\Models\Item;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

DB::transaction(function () {
    $feedAccount = ChartOfAccount::where('code', '5301')->first();
    $medicineAccount = ChartOfAccount::where('code', '5302')->first();
    $otherAccount = ChartOfAccount::where('code', '5303')->first();

    $invoices = PurchaseInvoice::with('items.item')->get();

    $count = 0;

    foreach ($invoices as $invoice) {
        $feedAmount = 0;
        $medicineAmount = 0;
        $otherAmount = 0;

        foreach ($invoice->items as $row) {
            $item = $row->item;
            if ($item) {
                $lineTotal = $row->quantity * $row->unit_price;
                if ($item->category === 'feed') {
                    $feedAmount += $lineTotal;
                } elseif ($item->category === 'medicine') {
                    $medicineAmount += $lineTotal;
                } elseif ($item->category === 'other') {
                    $otherAmount += $lineTotal;
                }
            }
        }

        $entry = JournalEntry::where('reference_type', 'purchase_invoice')
            ->where('reference_id', $invoice->id)
            ->first();

        if ($entry) {
            // Check if the purchase lines already exist
            $existingPurchaseLines = $entry->lines()
                ->whereIn('account_id', [$feedAccount?->id, $medicineAccount?->id, $otherAccount?->id])
                ->where('account_type', 'chart_of_account')
                ->count();

            if ($existingPurchaseLines == 0 && ($feedAmount > 0 || $medicineAmount > 0 || $otherAmount > 0)) {
                
                if ($feedAmount > 0 && $feedAccount) {
                    JournalEntryLine::create([
                        'journal_entry_id' => $entry->id,
                        'account_type'     => 'chart_of_account',
                        'account_id'       => $feedAccount->id,
                        'debit'            => $feedAmount,
                        'credit'           => 0,
                        'description'      => 'مشتريات علف - فاتورة شراء رقم ' . $invoice->invoice_number,
                    ]);
                }

                if ($medicineAmount > 0 && $medicineAccount) {
                    JournalEntryLine::create([
                        'journal_entry_id' => $entry->id,
                        'account_type'     => 'chart_of_account',
                        'account_id'       => $medicineAccount->id,
                        'debit'            => $medicineAmount,
                        'credit'           => 0,
                        'description'      => 'مشتريات أدوية - فاتورة شراء رقم ' . $invoice->invoice_number,
                    ]);
                }

                if ($otherAmount > 0 && $otherAccount) {
                    JournalEntryLine::create([
                        'journal_entry_id' => $entry->id,
                        'account_type'     => 'chart_of_account',
                        'account_id'       => $otherAccount->id,
                        'debit'            => $otherAmount,
                        'credit'           => 0,
                        'description'      => 'مشتريات عامة - فاتورة شراء رقم ' . $invoice->invoice_number,
                    ]);
                }
                
                $count++;
            }
        }
    }

    echo "Fixed {$count} old purchase invoices journal entries.\n";
});
