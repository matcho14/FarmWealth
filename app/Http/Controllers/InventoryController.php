<?php

namespace App\Http\Controllers;

use App\Models\Shed;
use App\Models\Item;
use App\Models\Cycle;
use App\Models\ShedInventory;
use App\Models\InventoryTransfer;
use App\Models\CycleDispensation;
use App\Models\FinancialRecord;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * عرض مخازن العنابر
     */
    public function index()
    {
        $sheds = Shed::with(['inventory.item'])->get();
        return view('inventory.index', compact('sheds'));
    }

    /**
     * صفحة التحويل من المخزن الرئيسي إلى العنبر
     */
    public function createTransfer()
    {
        $sheds = Shed::orderBy('name')->get();
        $items = Item::where('quantity_in_stock', '>', 0)->orderBy('name')->get();
        return view('inventory.transfer', compact('sheds', 'items'));
    }

    /**
     * تنفيذ التحويل من المخزن الرئيسي إلى العنبر
     */
    public function storeTransfer(Request $request)
    {
        $request->validate([
            'item_id'       => 'required|exists:items,id',
            'shed_id'       => 'required|exists:sheds,id',
            'quantity'      => 'required|numeric|min:0.001',
            'transfer_date' => 'required|date',
            'notes'         => 'nullable|string',
        ]);

        $item = Item::findOrFail($request->item_id);

        if ($item->quantity_in_stock < $request->quantity) {
            return back()->with('error', 'الكمية المطلوبة غير متوفرة في المخزن الرئيسي. المتاح: ' . $item->quantity_in_stock)->withInput();
        }

        DB::transaction(function () use ($request, $item) {
            // 1. خصم من المخزن الرئيسي
            $item->decrement('quantity_in_stock', $request->quantity);

            // 2. إضافة إلى مخزن العنبر (أو تحديثه)
            $shedInv = ShedInventory::firstOrCreate(
                ['shed_id' => $request->shed_id, 'item_id' => $request->item_id],
                ['quantity' => 0, 'avg_unit_cost' => $item->last_purchase_price]
            );

            // حساب متوسط التكلفة الجديد (مبسط: نستخدم سعر آخر شراء حالياً)
            $newTotalQty = $shedInv->quantity + $request->quantity;
            // $shedInv->avg_unit_cost = ($shedInv->quantity * $shedInv->avg_unit_cost + $request->quantity * $item->last_purchase_price) / $newTotalQty;
            $shedInv->avg_unit_cost = $item->last_purchase_price; 
            $shedInv->quantity = $newTotalQty;
            $shedInv->save();

            // 3. تسجيل حركة التحويل
            InventoryTransfer::create([
                'item_id'       => $request->item_id,
                'shed_id'       => $request->shed_id,
                'quantity'      => $request->quantity,
                'unit_cost'     => $item->last_purchase_price,
                'total_cost'    => $request->quantity * $item->last_purchase_price,
                'transfer_date' => $request->transfer_date,
                'notes'         => $request->notes,
            ]);
        });

        return redirect()->route('inventory.index')->with('success', 'تم تحويل الصنف إلى مخزن العنبر بنجاح');
    }

    /**
     * صفحة صرف صنف من مخزن العنبر إلى الدورة
     */
    public function createDispense(Cycle $cycle)
    {
        $shed = $cycle->shed;
        $inventory = ShedInventory::where('shed_id', $shed->id)
            ->where('quantity', '>', 0)
            ->with('item')
            ->get();
            
        return view('inventory.dispense', compact('cycle', 'shed', 'inventory'));
    }

    /**
     * تنفيذ الصرف من مخزن العنبر إلى الدورة (والدور المحدد)
     */
    public function storeDispense(Request $request, Cycle $cycle)
    {
        $request->validate([
            'item_id'           => 'required|exists:items,id',
            'floor_number'      => 'required|integer|min:1|max:' . $cycle->floors_count,
            'quantity'          => 'required|numeric|min:0.001',
            'dispensation_date' => 'required|date',
            'notes'             => 'nullable|string',
        ]);

        $shedInv = ShedInventory::where('shed_id', $cycle->shed_id)
            ->where('item_id', $request->item_id)
            ->first();

        if (!$shedInv || $shedInv->quantity < $request->quantity) {
            return back()->with('error', 'الكمية المطلوبة غير متوفرة في مخزن العنبر. المتاح: ' . ($shedInv->quantity ?? 0))->withInput();
        }

        DB::transaction(function () use ($request, $cycle, $shedInv) {
            // 1. خصم من مخزن العنبر
            $shedInv->decrement('quantity', $request->quantity);

            // 2. تسجيل حركة الصرف
            $dispensation = CycleDispensation::create([
                'cycle_id'          => $cycle->id,
                'shed_id'           => $cycle->shed_id,
                'item_id'           => $request->item_id,
                'floor_number'      => $request->floor_number,
                'quantity'          => $request->quantity,
                'unit_cost'         => $shedInv->avg_unit_cost,
                'total_cost'        => $request->quantity * $shedInv->avg_unit_cost,
                'dispensation_date' => $request->dispensation_date,
                'notes'             => $request->notes,
            ]);

            // 3. إضافة قيد مالي في الدورة (باعتباره مصروفاً)
            $item = Item::find($request->item_id);
            $record = FinancialRecord::create([
                'cycle_id'      => $cycle->id,
                'type'          => 'expense',
                'amount'        => $dispensation->total_cost,
                'quantity'      => $request->quantity,
                'floor_number'  => $request->floor_number,
                'item_id'       => $request->item_id,
                'dispensation_id' => $dispensation->id,
                'description'   => "صرف صنف: {$item->name} - دور {$request->floor_number} - " . ($request->notes ?? ''),
                'record_date'   => $request->dispensation_date,
            ]);

            // 4. إنشاء قيد محاسبي (غير نقدي - استهلاك مخزون)
            $entry = JournalEntry::create([
                'entry_number'   => JournalEntry::generateNumber(),
                'entry_date'     => $request->dispensation_date,
                'description'    => "قيد استهلاك مخزون - دورة #{$cycle->id} - {$item->name}",
                'reference_type' => 'financial_record',
                'reference_id'   => $record->id,
            ]);

            // من ح/ مصروفات الدورة (مدين)
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_type'     => 'cycle',
                'account_id'       => $cycle->id,
                'debit'            => $dispensation->total_cost,
                'credit'           => 0,
                'description'      => "استهلاك صنف {$item->name}",
            ]);

            // إلى ح/ المخزون (دائن) - لاحظ هنا نستخدم account_type='item' أو 'inventory'
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_type'     => 'item',
                'account_id'       => $item->id,
                'debit'            => 0,
                'credit'           => $dispensation->total_cost,
                'description'      => "صرف من مخزن العنبر",
            ]);
        });

        return redirect()->route('inventory.dispense.create', $cycle->id)->with('success', 'تم صرف الصنف على الدورة بنجاح، يمكنك إضافة صنف آخر');
    }

    /**
     * تصدير مخزون العنابر
     */
    public function exportInventory()
    {
        $sheds = Shed::with(['inventory.item'])->get();
        $filename = "Shed_Inventories_" . date('Y-m-d') . ".csv";
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($sheds) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            
            fputcsv($file, ['تقرير مخازن العنابر الفرعية']);
            fputcsv($file, ['تاريخ التقرير:', date('Y-m-d')]);
            fputcsv($file, []);
            
            fputcsv($file, ['العنبر', 'الصنف', 'الكمية المتاحة', 'الوحدة', 'متوسط التكلفة', 'إجمالي القيمة']);
            
            foreach ($sheds as $shed) {
                foreach ($shed->inventory as $inv) {
                    fputcsv($file, [
                        $shed->name,
                        $inv->item->name,
                        $inv->quantity,
                        $inv->item->unit,
                        $inv->avg_unit_cost,
                        $inv->quantity * $inv->avg_unit_cost
                    ]);
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
