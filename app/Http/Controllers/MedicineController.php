<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineEntry;
use App\Models\MedicineDispensation;
use App\Models\Cycle;
use App\Models\FinancialRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $query = Medicine::withSum('entries as total_stock', 'remaining_quantity');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $medicines = $query->get();
        return view('medicines.index', compact('medicines'));
    }

    public function create()
    {
        $units = \App\Models\Unit::all();
        return view('medicines.create', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        Medicine::create($request->all());

        return redirect()->route('medicines.index')->with('success', 'تم إضافة الدواء بنجاح');
    }

    public function show(Medicine $medicine)
    {
        $entries = $medicine->entries()->orderBy('entry_date', 'desc')->get();
        $dispensations = $medicine->dispensations()->with('cycle')->orderBy('dispensation_date', 'desc')->get();
        return view('medicines.show', compact('medicine', 'entries', 'dispensations'));
    }

    // شاشة إدخال كمية جديدة بسعرها
    public function createEntry(Medicine $medicine)
    {
        return view('medicines.create_entry', compact('medicine'));
    }

    public function storeEntry(Request $request, Medicine $medicine)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:0.01',
            'price' => 'required|numeric|min:0',
            'entry_date' => 'required|date',
        ]);

        $medicine->entries()->create([
            'quantity' => $request->quantity,
            'remaining_quantity' => $request->quantity,
            'price' => $request->price,
            'entry_date' => $request->entry_date,
        ]);

        return redirect()->route('medicines.index')->with('success', 'تم إضافة الكمية للمخزن بنجاح');
    }

    // شاشة الصرف على الدورة
    public function createDispensation()
    {
        $medicines = Medicine::all();
        $sheds = \App\Models\Shed::with('cycles')->get();
        return view('medicines.create_dispensation', compact('medicines', 'sheds'));
    }

    public function storeDispensation(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'cycle_id' => 'required|exists:cycles,id',
            'quantity' => 'required|numeric|min:0.01',
            'dispensation_date' => 'required|date',
        ]);

        $medicine = Medicine::findOrFail($request->medicine_id);
        $requestedQuantity = $request->quantity;

        // التحقق من توفر الكمية الإجمالية
        if ($medicine->entries()->sum('remaining_quantity') < $requestedQuantity) {
            return back()->with('error', 'الكمية المطلوبة غير متوفرة في المخزن');
        }

        DB::transaction(function () use ($medicine, $request, $requestedQuantity) {
            $remainingToDispense = $requestedQuantity;
            $totalCost = 0;

            // جلب الشحنات المتاحة بنظام FIFO (الأقدم أولاً)
            $entries = $medicine->entries()
                ->where('remaining_quantity', '>', 0)
                ->orderBy('entry_date', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            foreach ($entries as $entry) {
                if ($remainingToDispense <= 0) break;

                $take = min($remainingToDispense, $entry->remaining_quantity);
                $costForThisPart = $take * $entry->price;
                
                $entry->decrement('remaining_quantity', $take);
                $totalCost += $costForThisPart;
                $remainingToDispense -= $take;
            }

            // تسجيل عملية الصرف
            MedicineDispensation::create([
                'medicine_id' => $medicine->id,
                'cycle_id' => $request->cycle_id,
                'quantity' => $requestedQuantity,
                'total_cost' => $totalCost,
                'dispensation_date' => $request->dispensation_date,
            ]);

            // إضافة التكلفة كمصروف للدورة في السجلات المالية
            FinancialRecord::create([
                'cycle_id' => $request->cycle_id,
                'type' => 'expense',
                'amount' => $totalCost,
                'description' => "صرف دواء: {$medicine->name} (كمية: {$requestedQuantity})",
                'record_date' => $request->dispensation_date,
            ]);
        });

        return redirect()->route('medicines.index')->with('success', 'تم صرف الدواء وتسجيل المصاريف بنجاح');
    }
}
