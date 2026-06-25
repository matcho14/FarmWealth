<?php
namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index() {
        $items = Item::with('supplier')->get();
        return view('items.index', compact('items'));
    }

    public function show(Item $item) {
        $item->load([
            'invoiceItems.invoice.supplier',
            'transfers.shed',
            'dispensations.shed',
            'dispensations.cycle'
        ]);

        $main_movements = collect();
        foreach ($item->invoiceItems as $piItem) {
            if($piItem->invoice) {
                $main_movements->push([
                    'date' => $piItem->invoice->invoice_date,
                    'type' => 'وارد شراء',
                    'reference' => 'فاتورة #' . $piItem->invoice->invoice_number,
                    'in' => $piItem->quantity,
                    'out' => 0,
                    'price' => $piItem->unit_price,
                    'notes' => 'المورد: ' . ($piItem->invoice->supplier->name ?? '-'),
                    'timestamp' => $piItem->created_at,
                ]);
            }
        }
        foreach ($item->transfers as $transfer) {
            $main_movements->push([
                'date' => $transfer->transfer_date,
                'type' => 'تحويل إلى عنبر',
                'reference' => 'عنبر: ' . ($transfer->shed->name ?? '-'),
                'in' => 0,
                'out' => $transfer->quantity,
                'price' => $transfer->unit_cost,
                'notes' => $transfer->notes,
                'timestamp' => $transfer->created_at,
            ]);
        }
        $main_movements = $main_movements->sortBy(function($m) {
            return $m['date'] . ' ' . $m['timestamp'];
        })->values();

        $shed_movements = collect();
        foreach ($item->transfers as $transfer) {
            $shed_movements->push([
                'date' => $transfer->transfer_date,
                'type' => 'وارد من المخزن',
                'shed' => $transfer->shed->name ?? '-',
                'reference' => 'تحويل داخلي',
                'in' => $transfer->quantity,
                'out' => 0,
                'price' => $transfer->unit_cost,
                'notes' => $transfer->notes,
                'timestamp' => $transfer->created_at,
            ]);
        }
        foreach ($item->dispensations as $disp) {
            $shed_movements->push([
                'date' => $disp->dispensation_date,
                'type' => 'صرف لدورة',
                'shed' => $disp->shed->name ?? '-',
                'reference' => 'دورة #' . ($disp->cycle_id ?? '-'),
                'in' => 0,
                'out' => $disp->quantity,
                'price' => $disp->unit_cost,
                'notes' => 'دور: ' . $disp->floor_number . ' | ' . $disp->notes,
                'timestamp' => $disp->created_at,
            ]);
        }
        $shed_movements = $shed_movements->sortBy(function($m) {
            return $m['date'] . ' ' . $m['timestamp'];
        })->values();

        return view('items.show', compact('item', 'main_movements', 'shed_movements'));
    }

    public function create() {
        $suppliers = Supplier::orderBy('name')->get();
        $units = \App\Models\Unit::all();
        return view('items.create', compact('suppliers', 'units'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'category'      => 'required|in:feed,medicine,other',
            'unit'          => 'required|string|max:50',
            'supplier_id'   => 'nullable|exists:suppliers,id',
            'notes'         => 'nullable|string',
        ]);
        Item::create($data);
        return redirect()->route('items.index')->with('success','تم إضافة الصنف بنجاح');
    }

    public function edit(Item $item) {
        $suppliers = Supplier::orderBy('name')->get();
        $units = \App\Models\Unit::all();
        return view('items.edit', compact('item','suppliers', 'units'));
    }

    public function update(Request $request, Item $item) {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|in:feed,medicine,other',
            'unit'        => 'required|string|max:50',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'notes'       => 'nullable|string',
        ]);
        $item->update($data);
        return redirect()->route('items.index')->with('success','تم تحديث الصنف بنجاح');
    }

    public function destroy(Item $item) {
        $item->delete();
        return redirect()->route('items.index')->with('success','تم حذف الصنف');
    }
}
