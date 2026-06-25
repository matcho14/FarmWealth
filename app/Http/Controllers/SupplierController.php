<?php
namespace App\Http\Controllers;
use App\Models\Supplier;
use App\Models\JournalEntryLine;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index() {
        $suppliers = Supplier::all();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create() { return view('suppliers.create'); }

    public function store(Request $request) {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'phone'           => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            'address'         => 'nullable|string',
            'opening_balance' => 'nullable|numeric|min:0',
            'notes'           => 'nullable|string',
        ]);
        Supplier::create($data);
        return redirect()->route('suppliers.index')->with('success','تم إضافة المورد بنجاح');
    }

    public function show(Supplier $supplier) {
        $lines = JournalEntryLine::where('account_type','supplier')
            ->where('account_id', $supplier->id)
            ->with('journalEntry')
            ->orderBy('created_at','asc')
            ->get();

        $runningBalance = $supplier->opening_balance;
        $movements = [];
        foreach ($lines as $line) {
            $runningBalance += $line->credit - $line->debit;
            $movements[] = [
                'date'        => $line->journalEntry?->entry_date,
                'description' => $line->description ?? $line->journalEntry?->description,
                'reference'   => $line->journalEntry?->entry_number,
                'debit'       => $line->debit,
                'credit'      => $line->credit,
                'balance'     => $runningBalance,
            ];
        }
        return view('suppliers.show', compact('supplier','movements'));
    }

    public function exportStatement(Supplier $supplier)
    {
        $lines = JournalEntryLine::where('account_type','supplier')
            ->where('account_id', $supplier->id)
            ->with('journalEntry')
            ->orderBy('created_at','asc')
            ->get();

        $filename = "Supplier_Statement_" . str_replace(' ', '_', $supplier->name) . "_" . date('Y-m-d') . ".csv";
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($supplier, $lines) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM for Excel
            
            fputcsv($file, ['كشف حساب مورد:', $supplier->name]);
            fputcsv($file, ['التاريخ:', date('Y-m-d')]);
            fputcsv($file, ['الرصيد الافتتاحي:', $supplier->opening_balance]);
            fputcsv($file, []); // Empty line
            
            fputcsv($file, ['التاريخ', 'البيان', 'رقم المرجع', 'مدين (دفعات)', 'دائن (مشتريات)', 'الرصيد']);
            
            $runningBalance = $supplier->opening_balance;
            foreach ($lines as $line) {
                $runningBalance += $line->credit - $line->debit;
                fputcsv($file, [
                    $line->journalEntry?->entry_date->format('Y-m-d'),
                    $line->description ?? $line->journalEntry?->description,
                    $line->journalEntry?->entry_number,
                    $line->debit,
                    $line->credit,
                    $runningBalance
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function edit(Supplier $supplier) {
        if (!$supplier->hasChartAccount()) {
            return redirect()->route('chart-of-accounts.create', [
                'linkable_type' => \App\Models\Supplier::class,
                'linkable_id' => $supplier->id,
                'name' => $supplier->name,
                'suggested_code' => '220',
            ]);
        }
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier) {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'phone'           => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            'address'         => 'nullable|string',
            'opening_balance' => 'nullable|numeric|min:0',
            'notes'           => 'nullable|string',
        ]);
        $supplier->update($data);
        return redirect()->route('suppliers.index')->with('success','تم تحديث المورد بنجاح');
    }

    public function destroy(Supplier $supplier) {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success','تم حذف المورد');
    }
}
