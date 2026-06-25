<?php
namespace App\Http\Controllers;
use App\Models\Treasury;
use App\Models\JournalEntryLine;
use Illuminate\Http\Request;

class TreasuryController extends Controller
{
    public function index() {
        $treasuries = Treasury::all();
        return view('treasuries.index', compact('treasuries'));
    }

    public function create() { return view('treasuries.create'); }

    public function store(Request $request) {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'opening_balance' => 'nullable|numeric|min:0',
            'notes'           => 'nullable|string',
        ]);
        Treasury::create($data);
        return redirect()->route('treasuries.index')->with('success','تم إضافة الخزنة بنجاح');
    }

    public function show(Treasury $treasury) {
        $lines = JournalEntryLine::where('account_type','treasury')
            ->where('account_id', $treasury->id)
            ->with('journalEntry')
            ->orderBy('created_at','asc')
            ->get();

        $runningBalance = $treasury->opening_balance;
        $movements = [];
        foreach ($lines as $line) {
            $runningBalance += $line->debit - $line->credit;
            $movements[] = [
                'date'        => $line->journalEntry?->entry_date,
                'description' => $line->description ?? $line->journalEntry?->description,
                'reference'   => $line->journalEntry?->entry_number,
                'debit'       => $line->debit,
                'credit'      => $line->credit,
                'balance'     => $runningBalance,
            ];
        }
        return view('treasuries.show', compact('treasury','movements'));
    }

    public function exportStatement(Treasury $treasury)
    {
        $lines = JournalEntryLine::where('account_type','treasury')
            ->where('account_id', $treasury->id)
            ->with('journalEntry')
            ->orderBy('created_at','asc')
            ->get();

        $filename = "Treasury_Statement_" . str_replace(' ', '_', $treasury->name) . "_" . date('Y-m-d') . ".csv";
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($treasury, $lines) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM for Excel
            
            fputcsv($file, ['كشف حساب خزنة:', $treasury->name]);
            fputcsv($file, ['التاريخ:', date('Y-m-d')]);
            fputcsv($file, ['الرصيد الافتتاحي:', $treasury->opening_balance]);
            fputcsv($file, []); // Empty line
            
            fputcsv($file, ['التاريخ', 'البيان', 'رقم المرجع', 'وارد (مدين)', 'صادر (دائن)', 'الرصيد']);
            
            $runningBalance = $treasury->opening_balance;
            foreach ($lines as $line) {
                $runningBalance += $line->debit - $line->credit;
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

    public function edit(Treasury $treasury) { return view('treasuries.edit', compact('treasury')); }

    public function update(Request $request, Treasury $treasury) {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'opening_balance' => 'nullable|numeric|min:0',
            'notes'           => 'nullable|string',
        ]);
        $treasury->update($data);
        return redirect()->route('treasuries.index')->with('success','تم تحديث الخزنة');
    }

    public function destroy(Treasury $treasury) {
        $treasury->delete();
        return redirect()->route('treasuries.index')->with('success','تم حذف الخزنة');
    }
}
