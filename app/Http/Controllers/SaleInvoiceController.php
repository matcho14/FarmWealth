<?php
namespace App\Http\Controllers;
use App\Models\{SaleInvoice, SaleInvoiceItem, Item, Client, Treasury, JournalEntry, JournalEntryLine};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleInvoiceController extends Controller
{
    public function index() {
        $invoices = SaleInvoice::with('client')->latest()->paginate(20);
        return view('sale-invoices.index', compact('invoices'));
    }

    public function create() {
        $clients    = Client::orderBy('name')->get();
        $items      = Item::orderBy('name')->get();
        $treasuries = Treasury::orderBy('name')->get();
        $nextNumber = SaleInvoice::generateNumber();
        return view('sale-invoices.create', compact('clients','items','treasuries','nextNumber'));
    }

    public function store(Request $request) {
        $request->validate([
            'invoice_number' => 'required|string|unique:sale_invoices,invoice_number',
            'client_id'      => 'required|exists:clients,id',
            'invoice_date'   => 'required|date',
            'paid_amount'    => 'nullable|numeric|min:0',
            'treasury_id'    => 'nullable|exists:treasuries,id',
            'notes'          => 'nullable|string',
            'items'          => 'required|array|min:1',
            'items.*.item_id'    => 'required|exists:items,id',
            'items.*.quantity'   => 'required|numeric|min:0.001',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $itemsData  = $request->input('items');
        $totalAmount = collect($itemsData)->sum(fn($i) => $i['quantity'] * $i['unit_price']);
        $paidAmount  = (float)($request->paid_amount ?? 0);
        $paidAmount  = min($paidAmount, $totalAmount);

        if ($paidAmount > 0 && !$request->treasury_id) {
            return back()->withErrors(['treasury_id' => 'الخزنة مطلوبة عند إدخال مبلغ مدفوع'])->withInput();
        }

        DB::transaction(function () use ($request, $itemsData, $totalAmount, $paidAmount) {

            $status = 'unpaid';
            if ($paidAmount >= $totalAmount)   $status = 'paid';
            elseif ($paidAmount > 0)           $status = 'partial';

            $invoice = SaleInvoice::create([
                'invoice_number'  => $request->invoice_number,
                'client_id'       => $request->client_id,
                'treasury_id'     => $request->treasury_id,
                'invoice_date'    => $request->invoice_date,
                'total_amount'    => $totalAmount,
                'paid_amount'     => $paidAmount,
                'payment_status'  => $status,
                'notes'           => $request->notes,
            ]);

            foreach ($itemsData as $row) {
                $lineTotal = $row['quantity'] * $row['unit_price'];
                SaleInvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item_id'    => $row['item_id'],
                    'quantity'   => $row['quantity'],
                    'unit_price' => $row['unit_price'],
                    'total'      => $lineTotal,
                ]);
                $item = Item::find($row['item_id']);
                if ($item) {
                    $item->decrement('quantity_in_stock', $row['quantity']);
                }
            }

            $this->syncSaleInvoiceJournalEntries($invoice);
        });

        return redirect()->route('sale-invoices.index')->with('success','تم حفظ فاتورة البيع بنجاح');
    }

    public function show(SaleInvoice $saleInvoice) {
        $saleInvoice->load(['client','treasury','items.item']);
        return view('sale-invoices.show', compact('saleInvoice'));
    }

    public function edit(SaleInvoice $saleInvoice) {
        $saleInvoice->load(['client','treasury','items.item']);
        $clients    = Client::orderBy('name')->get();
        $items      = Item::orderBy('name')->get();
        $treasuries = Treasury::orderBy('name')->get();
        return view('sale-invoices.edit', compact('saleInvoice','clients','items','treasuries'));
    }

    public function update(Request $request, SaleInvoice $saleInvoice) {
        $request->validate([
            'invoice_number' => 'required|string|unique:sale_invoices,invoice_number,' . $saleInvoice->id,
            'client_id'      => 'required|exists:clients,id',
            'invoice_date'   => 'required|date',
            'paid_amount'    => 'nullable|numeric|min:0',
            'treasury_id'    => 'nullable|exists:treasuries,id',
            'notes'          => 'nullable|string',
            'items'          => 'required|array|min:1',
            'items.*.item_id'    => 'required|exists:items,id',
            'items.*.quantity'   => 'required|numeric|min:0.001',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $itemsData   = $request->input('items');
        $totalAmount = collect($itemsData)->sum(fn($i) => $i['quantity'] * $i['unit_price']);
        $paidAmount  = (float)($request->paid_amount ?? 0);
        $paidAmount  = min($paidAmount, $totalAmount);

        if ($paidAmount > 0 && !$request->treasury_id) {
            return back()->withErrors(['treasury_id' => 'الخزنة مطلوبة عند إدخال مبلغ مدفوع'])->withInput();
        }

        DB::transaction(function () use ($request, $saleInvoice, $itemsData, $totalAmount, $paidAmount) {
            foreach ($saleInvoice->items as $row) {
                $item = $row->item;
                if ($item) {
                    $item->increment('quantity_in_stock', $row->quantity);
                }
            }

            $saleInvoice->items()->delete();

            $status = 'unpaid';
            if ($paidAmount >= $totalAmount)   $status = 'paid';
            elseif ($paidAmount > 0)           $status = 'partial';

            $saleInvoice->update([
                'invoice_number'  => $request->invoice_number,
                'client_id'       => $request->client_id,
                'treasury_id'     => $request->treasury_id,
                'invoice_date'    => $request->invoice_date,
                'total_amount'    => $totalAmount,
                'paid_amount'     => $paidAmount,
                'payment_status'  => $status,
                'notes'           => $request->notes,
            ]);

            foreach ($itemsData as $row) {
                $lineTotal = $row['quantity'] * $row['unit_price'];
                SaleInvoiceItem::create([
                    'invoice_id' => $saleInvoice->id,
                    'item_id'    => $row['item_id'],
                    'quantity'   => $row['quantity'],
                    'unit_price' => $row['unit_price'],
                    'total'      => $lineTotal,
                ]);
                $item = Item::find($row['item_id']);
                if ($item) {
                    $item->decrement('quantity_in_stock', $row['quantity']);
                }
            }

            $this->syncSaleInvoiceJournalEntries($saleInvoice);
        });

        return redirect()->route('sale-invoices.index')->with('success','تم تعديل فاتورة البيع بنجاح');
    }

    private function syncSaleInvoiceJournalEntries(SaleInvoice $invoice): void
    {
        $this->deleteSaleInvoiceJournalEntries($invoice);

        $entry = JournalEntry::create([
            'entry_number'   => 'SAL-' . now()->format('Y') . '-' . str_pad($invoice->id, 4, '0', STR_PAD_LEFT),
            'entry_date'     => $invoice->invoice_date,
            'description'    => 'فاتورة بيع رقم ' . $invoice->invoice_number,
            'reference_type' => 'sale_invoice',
            'reference_id'   => $invoice->id,
        ]);

        if ($invoice->payment_status === 'paid' && (float)$invoice->paid_amount >= (float)$invoice->total_amount && $invoice->treasury_id) {
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_type'     => 'treasury',
                'account_id'       => $invoice->treasury_id,
                'debit'            => $invoice->total_amount,
                'credit'           => 0,
                'description'      => 'مبيعات كاش - فاتورة بيع رقم ' . $invoice->invoice_number,
            ]);
        } else {
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_type'     => 'client',
                'account_id'       => $invoice->client_id,
                'debit'            => $invoice->total_amount,
                'credit'           => 0,
                'description'      => 'مبيعات آجلة - فاتورة بيع رقم ' . $invoice->invoice_number,
            ]);
        }

        JournalEntryLine::create([
            'journal_entry_id' => $entry->id,
            'account_type'     => 'sales',
            'account_id'       => 0,
            'debit'            => 0,
            'credit'           => $invoice->total_amount,
            'description'      => 'إيرادات مبيعات - فاتورة بيع رقم ' . $invoice->invoice_number,
        ]);

        if ((float)$invoice->paid_amount > 0 && $invoice->treasury_id && (float)$invoice->paid_amount < (float)$invoice->total_amount) {
            $paymentEntry = JournalEntry::create([
                'entry_number'   => JournalEntry::generateNumber(),
                'entry_date'     => $invoice->invoice_date,
                'description'    => 'تحصيل فاتورة بيع رقم ' . $invoice->invoice_number,
                'reference_type' => 'sale_invoice_payment',
                'reference_id'   => $invoice->id,
            ]);

            JournalEntryLine::create([
                'journal_entry_id' => $paymentEntry->id,
                'account_type'     => 'treasury',
                'account_id'       => $invoice->treasury_id,
                'debit'            => $invoice->paid_amount,
                'credit'           => 0,
                'description'      => 'سداد من الخزينة مقابل فاتورة ' . $invoice->invoice_number,
            ]);

            JournalEntryLine::create([
                'journal_entry_id' => $paymentEntry->id,
                'account_type'     => 'client',
                'account_id'       => $invoice->client_id,
                'debit'            => 0,
                'credit'           => $invoice->paid_amount,
                'description'      => 'سداد العميل مقابل فاتورة ' . $invoice->invoice_number,
            ]);
        }
    }

    private function deleteSaleInvoiceJournalEntries(SaleInvoice $invoice): void
    {
        JournalEntry::where(function ($query) use ($invoice) {
            $query->where('reference_type', 'sale_invoice')
                ->orWhere(function ($query) use ($invoice) {
                    $query->where('reference_type', 'sale_invoice_payment')
                        ->where('reference_id', $invoice->id);
                })
                ->where('reference_id', $invoice->id);
        })->delete();
    }

    public function destroy(SaleInvoice $saleInvoice) {
        DB::transaction(function () use ($saleInvoice) {
            foreach ($saleInvoice->items as $row) {
                $item = $row->item;
                if ($item) {
                    $item->increment('quantity_in_stock', $row->quantity);
                }
            }

            $saleInvoice->items()->delete();
            $this->deleteSaleInvoiceJournalEntries($saleInvoice);
            $saleInvoice->delete();
        });

        return redirect()->route('sale-invoices.index')->with('success','تم حذف فاتورة البيع والقيود المرتبطة بها بنجاح');
    }
}
