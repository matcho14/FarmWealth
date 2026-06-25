<?php
namespace App\Http\Controllers;
use App\Models\{JournalEntry, JournalEntryLine, Supplier, Treasury, Client, FinancialRecord, PurchaseInvoice, SaleInvoice, ChartOfAccount};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JournalEntryController extends Controller
{
    public function index() {
        $entries = JournalEntry::with('lines')->latest()->paginate(20);
        return view('journal-entries.index', compact('entries'));
    }

    public function create() {
        $suppliers  = Supplier::orderBy('name')->get();
        $clients    = Client::orderBy('name')->get();
        $treasuries = Treasury::orderBy('name')->get();
        $chartAccounts = ChartOfAccount::active()->orderBy('code')->get();
        $nextNumber = JournalEntry::generateNumber();
        return view('journal-entries.create', compact('suppliers','clients','treasuries','nextNumber','chartAccounts'));
    }

    public function store(Request $request) {
        $request->validate([
            'entry_date'  => 'required|date',
            'description' => 'required|string|max:500',
            'notes'       => 'nullable|string',
            'lines'       => 'required|array|min:2',
            'lines.*.account_type' => 'required|in:supplier,client,treasury,chart_of_account,cycle,sales',
            'lines.*.account_id'   => 'required|integer|min:1',
            'lines.*.debit'        => 'nullable|numeric|min:0',
            'lines.*.credit'       => 'nullable|numeric|min:0',
            'lines.*.description'  => 'nullable|string|max:255',
        ]);

        $lines      = $request->input('lines');
        $totalDebit  = collect($lines)->sum(fn($l) => (float)($l['debit']  ?? 0));
        $totalCredit = collect($lines)->sum(fn($l) => (float)($l['credit'] ?? 0));

        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->withErrors(['lines' => 'مجموع المدين يجب أن يساوي مجموع الدائن'])->withInput();
        }

        DB::transaction(function () use ($request, $lines) {
            $entry = JournalEntry::create([
                'entry_number' => JournalEntry::generateNumber(),
                'entry_date'   => $request->entry_date,
                'description'  => $request->description,
                'reference_type' => 'manual',
                'notes'        => $request->notes,
            ]);

            foreach ($lines as $line) {
                JournalEntryLine::create([
                    'journal_entry_id' => $entry->id,
                    'account_type'     => $line['account_type'],
                    'account_id'       => $line['account_id'],
                    'debit'            => (float)($line['debit']  ?? 0),
                    'credit'           => (float)($line['credit'] ?? 0),
                    'description'      => $line['description'] ?? null,
                ]);
            }
        });

        return redirect()->route('journal-entries.index')->with('success','تم حفظ القيد بنجاح');
    }

    public function show(JournalEntry $journalEntry) {
        $journalEntry->load('lines');
        return view('journal-entries.show', compact('journalEntry'));
    }

    public function edit(JournalEntry $journalEntry) {
        if ($journalEntry->reference_type === 'manual') {
            $journalEntry->load('lines');
            $suppliers  = Supplier::orderBy('name')->get();
            $clients    = Client::orderBy('name')->get();
            $treasuries = Treasury::orderBy('name')->get();
            $chartAccounts = ChartOfAccount::active()->orderBy('code')->get();
            return view('journal-entries.edit', compact('journalEntry','suppliers','clients','treasuries','chartAccounts'));
        }

        return $this->redirectToSourceEdit($journalEntry);
    }

    public function update(Request $request, JournalEntry $journalEntry) {
        if ($journalEntry->reference_type === 'manual') {
            $request->validate([
                'entry_date'  => 'required|date',
                'description' => 'required|string|max:500',
                'notes'       => 'nullable|string',
                'lines'       => 'required|array|min:2',
                'lines.*.account_type' => 'required|in:supplier,client,treasury,chart_of_account,cycle,sales',
                'lines.*.account_id'   => 'required|integer|min:1',
                'lines.*.debit'        => 'nullable|numeric|min:0',
                'lines.*.credit'       => 'nullable|numeric|min:0',
                'lines.*.description'  => 'nullable|string|max:255',
            ]);

            $lines      = $request->input('lines');
            $totalDebit  = collect($lines)->sum(fn($l) => (float)($l['debit']  ?? 0));
            $totalCredit = collect($lines)->sum(fn($l) => (float)($l['credit'] ?? 0));

            if (abs($totalDebit - $totalCredit) > 0.01) {
                return back()->withErrors(['lines' => 'مجموع المدين يجب أن يساوي مجموع الدائن'])->withInput();
            }

            DB::transaction(function () use ($request, $journalEntry, $lines) {
                $journalEntry->update([
                    'entry_date'  => $request->entry_date,
                    'description' => $request->description,
                    'notes'       => $request->notes,
                ]);

                $journalEntry->lines()->delete();

                foreach ($lines as $line) {
                    JournalEntryLine::create([
                        'journal_entry_id' => $journalEntry->id,
                        'account_type'     => $line['account_type'],
                        'account_id'       => $line['account_id'],
                        'debit'            => (float)($line['debit']  ?? 0),
                        'credit'           => (float)($line['credit'] ?? 0),
                        'description'      => $line['description'] ?? null,
                    ]);
                }
            });

            return redirect()->route('journal-entries.index')->with('success','تم تعديل القيد بنجاح');
        }

        return $this->redirectToSourceEdit($journalEntry);
    }

    public function destroy(JournalEntry $journalEntry) {
        DB::transaction(function () use ($journalEntry) {
            $this->deleteLinkedSource($journalEntry);
            $journalEntry->delete();
        });

        return redirect()->route('journal-entries.index')->with('success','تم حذف القيد بنجاح');
    }

    private function redirectToSourceEdit(JournalEntry $entry)
    {
        if ($entry->reference_type === 'purchase_invoice') {
            $invoice = PurchaseInvoice::find($entry->reference_id);
            return $invoice
                ? redirect()->route('purchase-invoices.edit', $invoice)
                : redirect()->route('journal-entries.index')->with('error', 'فاتورة الشراء المرتبطة غير موجودة');
        }

        if ($entry->reference_type === 'sale_invoice') {
            $invoice = SaleInvoice::find($entry->reference_id);
            return $invoice
                ? redirect()->route('sale-invoices.edit', $invoice)
                : redirect()->route('journal-entries.index')->with('error', 'فاتورة البيع المرتبطة غير موجودة');
        }

        if ($entry->reference_type === 'sale_invoice_payment') {
            $invoice = SaleInvoice::find($entry->reference_id);
            return $invoice
                ? redirect()->route('sale-invoices.edit', $invoice)
                : redirect()->route('journal-entries.index')->with('error', 'فاتورة البيع المرتبطة غير موجودة');
        }

        if ($entry->reference_type === 'financial_record') {
            $record = FinancialRecord::with('cycle')->find($entry->reference_id);
            if (!$record) {
                return redirect()->route('journal-entries.index')->with('error', 'السجل المالي المرتبط غير موجود');
            }

            return $record->type === 'revenue'
                ? redirect()->route('cycles.editSale', [$record->cycle, $record])
                : redirect()->route('financial-records.edit', $record);
        }

        return redirect()->route('journal-entries.index')->with('error', 'لا يوجد مصدر تعديل لهذا القيد');
    }

    private function deleteLinkedSource(JournalEntry $entry): void
    {
        if ($entry->reference_type === 'purchase_invoice') {
            $invoice = PurchaseInvoice::find($entry->reference_id);
            if ($invoice) {
                foreach ($invoice->items as $row) {
                    $item = $row->item;
                    if ($item) {
                        $item->decrement('quantity_in_stock', $row->quantity);
                    }
                }
                $invoice->items()->delete();
                $invoice->delete();
            }
            return;
        }

        if ($entry->reference_type === 'sale_invoice') {
            $invoice = SaleInvoice::find($entry->reference_id);
            if ($invoice) {
                foreach ($invoice->items as $row) {
                    $item = $row->item;
                    if ($item) {
                        $item->increment('quantity_in_stock', $row->quantity);
                    }
                }
                $invoice->items()->delete();
                $invoice->delete();
            }
            return;
        }

        if ($entry->reference_type === 'sale_invoice_payment') {
            return;
        }

        if ($entry->reference_type === 'financial_record') {
            FinancialRecord::where('id', $entry->reference_id)->delete();
        }
    }
}
