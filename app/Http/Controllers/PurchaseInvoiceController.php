<?php
namespace App\Http\Controllers;
use App\Models\{PurchaseInvoice, PurchaseInvoiceItem, Item, Supplier, Treasury, JournalEntry, JournalEntryLine};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseInvoiceController extends Controller
{
    public function index() {
        $invoices = PurchaseInvoice::with('supplier')->latest()->paginate(20);
        return view('purchase-invoices.index', compact('invoices'));
    }

    public function create() {
        $suppliers  = Supplier::orderBy('name')->get();
        $items      = Item::orderBy('name')->get();
        $treasuries = Treasury::orderBy('name')->get();
        $nextNumber = PurchaseInvoice::generateNumber();
        return view('purchase-invoices.create', compact('suppliers','items','treasuries','nextNumber'));
    }

    public function store(Request $request) {
        $request->validate([
            'invoice_number' => 'required|string|unique:purchase_invoices,invoice_number',
            'supplier_id'    => 'required|exists:suppliers,id',
            'invoice_date'   => 'required|date',
            'paid_amount'    => 'nullable|numeric|min:0',
            'treasury_id'    => 'nullable|exists:treasuries,id',
            'notes'          => 'nullable|string',
            'items'          => 'required|array|min:1',
            'items.*.item_id'    => 'required|exists:items,id',
            'items.*.quantity'   => 'required|numeric|min:0.001',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $itemsData   = $request->input('items');
            $totalAmount = collect($itemsData)->sum(fn($i) => $i['quantity'] * $i['unit_price']);
            $paidAmount  = (float)($request->paid_amount ?? 0);
            $paidAmount  = min($paidAmount, $totalAmount);

            $status = 'unpaid';
            if ($paidAmount >= $totalAmount)   $status = 'paid';
            elseif ($paidAmount > 0)           $status = 'partial';

            $invoice = PurchaseInvoice::create([
                'invoice_number'  => $request->invoice_number,
                'supplier_id'     => $request->supplier_id,
                'treasury_id'     => $request->treasury_id,
                'invoice_date'    => $request->invoice_date,
                'total_amount'    => $totalAmount,
                'paid_amount'     => $paidAmount,
                'payment_status'  => $status,
                'notes'           => $request->notes,
            ]);

            foreach ($itemsData as $row) {
                $lineTotal = $row['quantity'] * $row['unit_price'];
                PurchaseInvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item_id'    => $row['item_id'],
                    'quantity'   => $row['quantity'],
                    'unit_price' => $row['unit_price'],
                    'total'      => $lineTotal,
                ]);
                $item = Item::find($row['item_id']);
                if ($item) {
                    $item->increment('quantity_in_stock', $row['quantity']);
                    $item->update(['last_purchase_price' => $row['unit_price']]);
                }
            }

            $entry = JournalEntry::create([
                'entry_number'   => 'PUR-' . now()->format('Y') . '-' . str_pad($invoice->id, 4, '0', STR_PAD_LEFT),
                'entry_date'     => $invoice->invoice_date,
                'description'    => 'فاتورة شراء رقم ' . $invoice->invoice_number,
                'reference_type' => 'purchase_invoice',
                'reference_id'   => $invoice->id,
            ]);

            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_type'     => 'supplier',
                'account_id'       => $invoice->supplier_id,
                'debit'            => 0,
                'credit'           => $totalAmount,
                'description'      => 'فاتورة شراء رقم ' . $invoice->invoice_number,
            ]);

            if ($paidAmount > 0 && $invoice->treasury_id) {
                JournalEntryLine::create([
                    'journal_entry_id' => $entry->id,
                    'account_type'     => 'supplier',
                    'account_id'       => $invoice->supplier_id,
                    'debit'            => $paidAmount,
                    'credit'           => 0,
                    'description'      => 'دفعة مقابل فاتورة ' . $invoice->invoice_number,
                ]);
                JournalEntryLine::create([
                    'journal_entry_id' => $entry->id,
                    'account_type'     => 'treasury',
                    'account_id'       => $invoice->treasury_id,
                    'debit'            => 0,
                    'credit'           => $paidAmount,
                    'description'      => 'دفع فاتورة شراء رقم ' . $invoice->invoice_number,
                ]);
            }
        });

        return redirect()->route('purchase-invoices.index')->with('success','تم حفظ فاتورة الشراء بنجاح');
    }

    public function show(PurchaseInvoice $purchaseInvoice) {
        $purchaseInvoice->load(['supplier','treasury','items.item']);
        return view('purchase-invoices.show', compact('purchaseInvoice'));
    }

    public function edit(PurchaseInvoice $purchaseInvoice) {
        $purchaseInvoice->load(['supplier','treasury','items.item']);
        $suppliers  = Supplier::orderBy('name')->get();
        $items      = Item::orderBy('name')->get();
        $treasuries = Treasury::orderBy('name')->get();
        return view('purchase-invoices.edit', compact('purchaseInvoice','suppliers','items','treasuries'));
    }

    public function update(Request $request, PurchaseInvoice $purchaseInvoice) {
        $request->validate([
            'invoice_number' => 'required|string|unique:purchase_invoices,invoice_number,' . $purchaseInvoice->id,
            'supplier_id'    => 'required|exists:suppliers,id',
            'invoice_date'   => 'required|date',
            'paid_amount'    => 'nullable|numeric|min:0',
            'treasury_id'    => 'nullable|exists:treasuries,id',
            'notes'          => 'nullable|string',
            'items'          => 'required|array|min:1',
            'items.*.item_id'    => 'required|exists:items,id',
            'items.*.quantity'   => 'required|numeric|min:0.001',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $purchaseInvoice) {
            foreach ($purchaseInvoice->items as $row) {
                $item = $row->item;
                if ($item) {
                    $item->decrement('quantity_in_stock', $row->quantity);
                }
            }

            $purchaseInvoice->items()->delete();

            $itemsData   = $request->input('items');
            $totalAmount = collect($itemsData)->sum(fn($i) => $i['quantity'] * $i['unit_price']);
            $paidAmount  = (float)($request->paid_amount ?? 0);
            $paidAmount  = min($paidAmount, $totalAmount);

            $status = 'unpaid';
            if ($paidAmount >= $totalAmount)   $status = 'paid';
            elseif ($paidAmount > 0)           $status = 'partial';

            $purchaseInvoice->update([
                'invoice_number'  => $request->invoice_number,
                'supplier_id'     => $request->supplier_id,
                'treasury_id'     => $request->treasury_id,
                'invoice_date'    => $request->invoice_date,
                'total_amount'    => $totalAmount,
                'paid_amount'     => $paidAmount,
                'payment_status'  => $status,
                'notes'           => $request->notes,
            ]);

            foreach ($itemsData as $row) {
                $lineTotal = $row['quantity'] * $row['unit_price'];
                PurchaseInvoiceItem::create([
                    'invoice_id' => $purchaseInvoice->id,
                    'item_id'    => $row['item_id'],
                    'quantity'   => $row['quantity'],
                    'unit_price' => $row['unit_price'],
                    'total'      => $lineTotal,
                ]);
                $item = Item::find($row['item_id']);
                if ($item) {
                    $item->increment('quantity_in_stock', $row['quantity']);
                    $item->update(['last_purchase_price' => $row['unit_price']]);
                }
            }

            $entry = JournalEntry::where('reference_type', 'purchase_invoice')
                ->where('reference_id', $purchaseInvoice->id)
                ->first();

            if (!$entry) {
                $entry = JournalEntry::create([
                    'entry_number'   => 'PUR-' . now()->format('Y') . '-' . str_pad($purchaseInvoice->id, 4, '0', STR_PAD_LEFT),
                    'entry_date'     => $purchaseInvoice->invoice_date,
                    'description'    => 'فاتورة شراء رقم ' . $purchaseInvoice->invoice_number,
                    'reference_type' => 'purchase_invoice',
                    'reference_id'   => $purchaseInvoice->id,
                ]);
            }

            $entry->update([
                'entry_date'  => $purchaseInvoice->invoice_date,
                'description' => 'فاتورة شراء رقم ' . $purchaseInvoice->invoice_number,
            ]);

            $entry->lines()->delete();

            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_type'     => 'supplier',
                'account_id'       => $purchaseInvoice->supplier_id,
                'debit'            => 0,
                'credit'           => $totalAmount,
                'description'      => 'فاتورة شراء رقم ' . $purchaseInvoice->invoice_number,
            ]);

            if ($paidAmount > 0 && $purchaseInvoice->treasury_id) {
                JournalEntryLine::create([
                    'journal_entry_id' => $entry->id,
                    'account_type'     => 'supplier',
                    'account_id'       => $purchaseInvoice->supplier_id,
                    'debit'            => $paidAmount,
                    'credit'           => 0,
                    'description'      => 'دفعة مقابل فاتورة ' . $purchaseInvoice->invoice_number,
                ]);
                JournalEntryLine::create([
                    'journal_entry_id' => $entry->id,
                    'account_type'     => 'treasury',
                    'account_id'       => $purchaseInvoice->treasury_id,
                    'debit'            => 0,
                    'credit'           => $paidAmount,
                    'description'      => 'دفع فاتورة شراء رقم ' . $purchaseInvoice->invoice_number,
                ]);
            }
        });

        return redirect()->route('purchase-invoices.index')->with('success','تم تعديل فاتورة الشراء بنجاح');
    }

    public function destroy(PurchaseInvoice $purchaseInvoice) {
        DB::transaction(function () use ($purchaseInvoice) {
            foreach ($purchaseInvoice->items as $row) {
                $item = $row->item;
                if ($item) {
                    $item->decrement('quantity_in_stock', $row->quantity);
                }
            }

            $purchaseInvoice->items()->delete();
            JournalEntry::where('reference_type', 'purchase_invoice')
                ->where('reference_id', $purchaseInvoice->id)
                ->delete();
            $purchaseInvoice->delete();
        });

        return redirect()->route('purchase-invoices.index')->with('success','تم حذف فاتورة الشراء والقيود المرتبطة بها بنجاح');
    }
}
