<?php

namespace App\Http\Controllers;

use App\Models\Cycle;
use App\Models\MortalityRecord;
use App\Models\Shed;
use App\Models\Treasury;
use App\Models\Client;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use App\Models\FinancialRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\ShedScoped;

class CycleController extends Controller
{
    use ShedScoped;
    /**
     * عرض قائمة الدورات
     */
    public function index()
    {
        $cycles = $this->applyShedScope(Cycle::with('shed'))->paginate(15);

        return view('cycles.index', compact('cycles'));
    }

    /**
     * عرض نموذج إنشاء دورة جديدة
     */
    public function create(Shed $shed)
    {
        return view('cycles.create', compact('shed'));
    }

    /**
     * حفظ دورة جديدة
     */
    public function store(Request $request, Shed $shed)
    {
        $floors = $shed->floors;

        $rules = [
            'start_date' => 'required|date',
        ];
        $messages = [
            'start_date.required' => 'تاريخ البداية مطلوب',
        ];

        for ($i = 1; $i <= $floors; $i++) {
            $rules["floor_chicks.{$i}"] = 'required|integer|min:0';
            $messages["floor_chicks.{$i}.required"] = "عدد الكتاكيت في الدور {$i} مطلوب";
            $messages["floor_chicks.{$i}.min"] = "عدد الكتاكيت في الدور {$i} يجب أن يكون 0 أو أكثر";
        }

        $validated = $request->validate($rules, $messages);
        $floorChicks = $request->input('floor_chicks', []);
        $totalChicks = array_sum($floorChicks);

        if ($totalChicks < 1) {
            return back()->withErrors(['floor_chicks' => 'يجب أن يكون إجمالي الكتاكيت أكثر من 0'])->withInput();
        }

        $cleanFloorChicks = [];
        for ($i = 1; $i <= $floors; $i++) {
            $cleanFloorChicks[$i] = (int)($floorChicks[$i] ?? 0);
        }

        Cycle::create([
            'shed_id'       => $shed->id,
            'start_date'    => $validated['start_date'],
            'initial_chicks' => $totalChicks,
            'floor_chicks'  => $cleanFloorChicks,
        ]);

        return redirect()->route('sheds.show', $shed)->with('success', 'تم إنشاء الدورة بنجاح');
    }

    /**
     * عرض تفاصيل الدورة
     */
    public function show(Cycle $cycle)
    {
        $this->assertShedAccess($cycle->shed);
        $cycle->load(['shed', 'financialRecords' => function($q) {
            $q->orderBy('record_date', 'desc')->orderBy('id', 'desc');
        }, 'financialRecords.client', 'mortalityRecords' => function($q) {
            $q->orderBy('record_date', 'desc')->orderBy('id', 'desc');
        }]);

        return view('cycles.show', compact('cycle'));
    }

    /**
     * عرض نموذج إضافة النافق
     */
    public function editMortality(Cycle $cycle)
    {
        $cycle->load('shed');
        $floors = $cycle->shed->floors;
        $floorChicks = $cycle->floor_chicks ?? [];
        $mortalityByFloor = $cycle->mortalityByFloor;

        $remainingByFloor = [];
        for ($i = 1; $i <= $floors; $i++) {
            $initial = (int)($floorChicks[$i] ?? 0);
            $dead    = (int)($mortalityByFloor[$i] ?? 0);
            $remainingByFloor[$i] = $initial - $dead;
        }

        return view('cycles.edit-mortality', compact('cycle', 'floors', 'floorChicks', 'mortalityByFloor', 'remainingByFloor'));
    }

    /**
     * حفظ النافق (مع تحديد الدور)
     */
    public function updateMortality(Request $request, Cycle $cycle)
    {
        $cycle->load('shed');
        $floors = $cycle->shed->floors;
        $floorChicks = $cycle->floor_chicks ?? [];
        $mortalityByFloor = $cycle->mortalityByFloor;

        $rules    = ['record_date' => 'required|date'];
        $messages = [];

        for ($i = 1; $i <= $floors; $i++) {
            $max = max(0, (int)($floorChicks[$i] ?? 0) - (int)($mortalityByFloor[$i] ?? 0));
            $rules["floor_mortality.{$i}"] = "nullable|integer|min:0|max:{$max}";
            $messages["floor_mortality.{$i}.max"] = "النافق في الدور {$i} لا يمكن أن يزيد عن المتبقي ({$max})";
        }

        $validated = $request->validate($rules, $messages);
        $floorMortality = $request->input('floor_mortality', []);
        $notes = $request->input('notes');
        $recordDate = $validated['record_date'];

        $totalAdded = 0;
        for ($i = 1; $i <= $floors; $i++) {
            $count = (int)($floorMortality[$i] ?? 0);
            if ($count > 0) {
                MortalityRecord::create([
                    'cycle_id'    => $cycle->id,
                    'floor_number' => $i,
                    'count'        => $count,
                    'record_date'  => $recordDate,
                    'notes'        => $notes,
                ]);
                $totalAdded += $count;
            }
        }

        if ($totalAdded === 0) {
            return back()->withErrors(['floor_mortality' => 'يجب إدخال عدد نافق في دور واحد على الأقل'])->withInput();
        }

        return redirect()->route('cycles.show', $cycle)->with('success', "تم تسجيل {$totalAdded} نافق بنجاح");
    }

    public function showMortality(Cycle $cycle, MortalityRecord $mortalityRecord)
    {
        if ($mortalityRecord->cycle_id !== $cycle->id) {
            abort(404);
        }

        return view('cycles.mortality-record.show', compact('cycle', 'mortalityRecord'));
    }

    public function editMortalityRecord(Cycle $cycle, MortalityRecord $mortalityRecord)
    {
        if ($mortalityRecord->cycle_id !== $cycle->id) {
            abort(404);
        }

        $cycle->load('shed');
        $floors = $cycle->shed->floors;
        $floorChicks = $cycle->floor_chicks ?? [];
        $otherMortalityByFloor = $cycle->mortalityRecords()
            ->where('id', '!=', $mortalityRecord->id)
            ->selectRaw('floor_number, SUM(count) as total')
            ->groupBy('floor_number')
            ->pluck('total', 'floor_number')
            ->toArray();

        $remainingByFloor = [];
        for ($i = 1; $i <= $floors; $i++) {
            $remainingByFloor[$i] = max(0, (int)($floorChicks[$i] ?? 0) - (int)($otherMortalityByFloor[$i] ?? 0));
        }

        return view('cycles.edit-mortality-record', compact('cycle', 'floors', 'floorChicks', 'mortalityRecord', 'remainingByFloor'));
    }

    public function updateMortalityRecord(Request $request, Cycle $cycle, MortalityRecord $mortalityRecord)
    {
        if ($mortalityRecord->cycle_id !== $cycle->id) {
            abort(404);
        }

        $cycle->load('shed');
        $floors = $cycle->shed->floors;
        $floorChicks = $cycle->floor_chicks ?? [];
        $floorNumber = (int)$request->input('floor_number');

        if ($floorNumber < 1 || $floorNumber > $floors) {
            return back()->withErrors(['floor_number' => 'الدور المختار غير صحيح'])->withInput();
        }

        $otherMortalityByFloor = $cycle->mortalityRecords()
            ->where('id', '!=', $mortalityRecord->id)
            ->selectRaw('floor_number, SUM(count) as total')
            ->groupBy('floor_number')
            ->pluck('total', 'floor_number')
            ->toArray();

        $maxCount = max(0, (int)($floorChicks[$floorNumber] ?? 0) - (int)($otherMortalityByFloor[$floorNumber] ?? 0));

        $validated = $request->validate([
            'record_date' => 'required|date',
            'floor_number' => 'required|integer|min:1|max:' . $floors,
            'count' => 'required|integer|min:0|max:' . $maxCount,
            'notes' => 'nullable|string',
        ]);

        $mortalityRecord->update($validated);

        return redirect()->route('cycles.show', $cycle)->with('success', 'تم تعديل سجل النافق بنجاح');
    }

    public function destroyMortalityRecord(Cycle $cycle, MortalityRecord $mortalityRecord)
    {
        if ($mortalityRecord->cycle_id !== $cycle->id) {
            abort(404);
        }

        $mortalityRecord->delete();

        return redirect()->route('cycles.show', $cycle)->with('success', 'تم حذف سجل النافق بنجاح');
    }

    /**
     * عرض نموذج إضافة مبيعات
     */
    public function createSales(Cycle $cycle)
    {
        if ($cycle->status !== 'active') {
            return redirect()->route('cycles.show', $cycle)->with('error', 'هذه الدورة مغلقة بالفعل');
        }

        $clients = Client::orderBy('name')->get();
        $treasuries = Treasury::orderBy('name')->get();
        return view('cycles.create-sales', compact('cycle', 'clients', 'treasuries'));
    }

    public function storeSales(Request $request, Cycle $cycle)
    {
        if ($cycle->status !== 'active') {
            return redirect()->route('cycles.show', $cycle)->with('error', 'هذه الدورة مغلقة بالفعل');
        }

        $validated = $request->validate([
            'sold_count'   => 'required|integer|min:1|max:' . $cycle->expected_remaining,
            'weight'       => 'required|numeric|min:0',
            'amount'       => 'required|numeric|min:0',
            'paid_amount'  => 'nullable|numeric|min:0',
            'record_date'  => 'required|date',
            'description'  => 'nullable|string',
            'payment_type' => 'required|in:cash,credit',
            'client_id'    => 'required|exists:clients,id',
            'treasury_id'  => 'required_if:payment_type,cash|nullable|exists:treasuries,id',
        ], [
            'sold_count.required' => 'عدد الكتاكيت المباعة مطلوب',
            'sold_count.min'      => 'عدد المبيعات يجب أن يكون أكثر من 0',
            'sold_count.max'      => 'عدد المبيعات لا يمكن أن يزيد عن الكتاكيت المتبقية',
            'weight.required'     => 'وزن الكمية مطلوب',
            'amount.required'     => 'مبلغ الإيرادات مطلوب',
            'record_date.required' => 'التاريخ مطلوب',
            'payment_type.required' => 'نوع الدفع مطلوب',
            'client_id.required' => 'العميل مطلوب',
            'treasury_id.required_if' => 'الخزنة مطلوبة للمبيعات الكاش',
        ]);

        DB::transaction(function () use ($validated, $cycle) {
            $description = $validated['description'] ?? 'مبيعات كتاكيت (' . $validated['sold_count'] . ')';

            $record = $cycle->financialRecords()->create([
                'type'         => 'revenue',
                'quantity'     => $validated['sold_count'],
                'weight'       => $validated['weight'],
                'amount'       => $validated['amount'],
                'paid_amount'  => $validated['payment_type'] === 'cash' ? $validated['amount'] : ($validated['paid_amount'] ?? 0),
                'description'  => $description,
                'record_date'  => $validated['record_date'],
                'payment_type' => $validated['payment_type'],
                'client_id'    => $validated['client_id'],
                'treasury_id'  => $validated['treasury_id'],
            ]);

            $this->syncSaleJournalEntry($record, $validated, $cycle);
        });

        $msg = $validated['payment_type'] === 'cash'
            ? 'تم تسجيل المبيعات كاش للعميل ' . Client::find($validated['client_id'])->name
            : 'تم تسجيل المبيعات اجل للعميل ' . Client::find($validated['client_id'])->name;
        return redirect()->route('cycles.show', $cycle)->with('success', $msg);
    }

    public function showSale(Cycle $cycle, FinancialRecord $financialRecord)
    {
        if ($financialRecord->cycle_id !== $cycle->id || $financialRecord->type !== 'revenue') {
            abort(404);
        }

        $financialRecord->load(['cycle','client','treasury']);
        return view('cycles.sales.show', compact('cycle', 'financialRecord'));
    }

    public function editSale(Cycle $cycle, FinancialRecord $financialRecord)
    {
        if ($financialRecord->cycle_id !== $cycle->id || $financialRecord->type !== 'revenue') {
            abort(404);
        }

        $clients = Client::orderBy('name')->get();
        $treasuries = Treasury::orderBy('name')->get();
        return view('cycles.edit-sales', compact('cycle', 'financialRecord', 'clients', 'treasuries'));
    }

    public function updateSale(Request $request, Cycle $cycle, FinancialRecord $financialRecord)
    {
        if ($financialRecord->cycle_id !== $cycle->id || $financialRecord->type !== 'revenue') {
            abort(404);
        }

        $oldQuantity = (int)($financialRecord->quantity ?? 0);
        $maxSoldCount = max(1, $cycle->expected_remaining + $oldQuantity);

        $validated = $request->validate([
            'sold_count'   => 'required|integer|min:1|max:' . $maxSoldCount,
            'weight'       => 'required|numeric|min:0',
            'amount'       => 'required|numeric|min:0',
            'paid_amount'  => 'nullable|numeric|min:0',
            'record_date'  => 'required|date',
            'description'  => 'nullable|string',
            'payment_type' => 'required|in:cash,credit',
            'client_id'    => 'required|exists:clients,id',
            'treasury_id'  => 'required_if:payment_type,cash|nullable|exists:treasuries,id',
        ], [
            'sold_count.required' => 'عدد الكتاكيت المباعة مطلوب',
            'sold_count.min'      => 'عدد المبيعات يجب أن يكون أكثر من 0',
            'sold_count.max'      => 'عدد المبيعات لا يمكن أن يزيد عن الكتاكيت المتبقية',
            'weight.required'     => 'وزن الكمية مطلوب',
            'amount.required'     => 'مبلغ الإيرادات مطلوب',
            'record_date.required' => 'التاريخ مطلوب',
            'payment_type.required' => 'نوع الدفع مطلوب',
            'client_id.required' => 'العميل مطلوب',
            'treasury_id.required_if' => 'الخزنة مطلوبة للمبيعات الكاش',
        ]);

        DB::transaction(function () use ($validated, $cycle, $financialRecord) {
            $description = $validated['description'] ?? 'مبيعات كتاكيت (' . $validated['sold_count'] . ')';
            $paidAmount = min((float)($validated['paid_amount'] ?? 0), (float)$validated['amount']);

            $financialRecord->update([
                'quantity'     => $validated['sold_count'],
                'weight'       => $validated['weight'],
                'amount'       => $validated['amount'],
                'paid_amount'  => $paidAmount,
                'description'  => $description,
                'record_date'  => $validated['record_date'],
                'payment_type' => $validated['payment_type'],
                'client_id'    => $validated['client_id'],
                'treasury_id'  => $validated['treasury_id'],
            ]);

            $this->syncSaleJournalEntry($financialRecord, $validated, $cycle);
        });

        return redirect()->route('cycles.show', $cycle)->with('success', 'تم تعديل مبيعات الدورة بنجاح');
    }

    public function createPayment(Cycle $cycle, FinancialRecord $financialRecord)
    {
        if ($financialRecord->cycle_id !== $cycle->id || $financialRecord->type !== 'revenue' || $financialRecord->payment_type !== 'credit') {
            abort(404);
        }

        $treasuries = Treasury::orderBy('name')->get();
        $remaining = max(0, (float)$financialRecord->amount - (float)$financialRecord->paid_amount);

        return view('cycles.sales.payment', compact('cycle', 'financialRecord', 'treasuries', 'remaining'));
    }

    public function storePayment(Request $request, Cycle $cycle, FinancialRecord $financialRecord)
    {
        if ($financialRecord->cycle_id !== $cycle->id || $financialRecord->type !== 'revenue' || $financialRecord->payment_type !== 'credit') {
            abort(404);
        }

        $remaining = max(0, (float)$financialRecord->amount - (float)$financialRecord->paid_amount);

        $validated = $request->validate([
            'amount'      => 'required|numeric|min:0.01|max:' . $remaining,
            'treasury_id' => 'required|exists:treasuries,id',
            'record_date' => 'required|date|before_or_equal:today',
        ], [
            'amount.max' => 'مبلغ التحصيل لا يمكن أن يزيد عن المتبقي (' . format_number($remaining, 2) . ')',
            'treasury_id.required' => 'الخزنة مطلوبة',
            'record_date.required' => 'تاريخ التحصيل مطلوب',
            'record_date.before_or_equal' => 'لا يمكن إدخال تواريخ مستقبلية',
        ]);

        DB::transaction(function () use ($validated, $cycle, $financialRecord) {
            $amount = (float)$validated['amount'];
            $entry = JournalEntry::create([
                'entry_number'   => JournalEntry::generateNumber(),
                'entry_date'     => $validated['record_date'],
                'description'    => 'تحصيل مبيعات دورة: ' . ($financialRecord->description ?? 'مبيعات دورة'),
                'reference_type' => 'financial_record_payment',
                'reference_id'   => $financialRecord->id,
            ]);

            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_type'     => 'treasury',
                'account_id'       => $validated['treasury_id'],
                'debit'            => $amount,
                'credit'           => 0,
                'description'      => 'تحصيل من العميل مقابل مبيعات الدورة',
            ]);

            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_type'     => 'client',
                'account_id'       => $financialRecord->client_id,
                'debit'            => 0,
                'credit'           => $amount,
                'description'      => 'سداد جزء من مبيعات الدورة الآجلة',
            ]);

            $financialRecord->increment('paid_amount', $amount);
            if ((float)$financialRecord->paid_amount >= (float)$financialRecord->amount) {
                $financialRecord->update(['paid_amount' => $financialRecord->amount]);
            }
        });

        return redirect()->route('cycles.sales.show', [$cycle, $financialRecord])->with('success', 'تم تسجيل التحصيل بنجاح');
    }

    public function destroySale(Cycle $cycle, FinancialRecord $financialRecord)
    {
        if ($financialRecord->cycle_id !== $cycle->id || $financialRecord->type !== 'revenue') {
            abort(404);
        }

        DB::transaction(function () use ($financialRecord) {
            JournalEntry::where('reference_type', 'financial_record')
                ->where('reference_id', $financialRecord->id)
                ->delete();

            JournalEntry::where('reference_type', 'financial_record_payment')
                ->where('reference_id', $financialRecord->id)
                ->delete();

            $financialRecord->delete();
        });

        return redirect()->route('cycles.show', $cycle)->with('success', 'تم حذف مبيعات الدورة بنجاح');
    }

    /**
     * عرض نموذج إغلاق الدورة
     */
    public function closeCycleForm(Cycle $cycle)
    {
        if ($cycle->status !== 'active') {
            return redirect()->route('cycles.show', $cycle)->with('error', 'هذه الدورة مغلقة بالفعل');
        }

        return view('cycles.close', compact('cycle'));
    }

    /**
     * إغلاق الدورة
     */
    public function closeCycle(Request $request, Cycle $cycle)
    {
        $validated = $request->validate([
            'sold_chicks' => 'required|integer|min:0|max:' . $cycle->expected_remaining,
            'sold_weight' => 'required|numeric|min:0',
        ], [
            'sold_chicks.required' => 'عدد الكتاكيت المباعة مطلوب',
            'sold_chicks.integer'  => 'يجب أن يكون عدد صحيح',
            'sold_chicks.max'      => 'عدد المبيعات لا يمكن أن يزيد عن المتبقي',
            'sold_weight.required' => 'الوزن مطلوب',
        ]);

        $result = $cycle->closeCycle($validated['sold_chicks'], $validated['sold_weight']);

        return redirect()->route('cycles.show', $cycle)->with([
            'success'     => $result['message'],
            'discrepancy' => $result['discrepancy'],
        ]);
    }

    /**
     * استخراج كشف الحساب
     */
    public function exportStatement(Cycle $cycle)
    {
        $cycle->load(['financialRecords' => function($q) {
            $q->orderBy('record_date', 'asc')->orderBy('id', 'asc');
        }]);

        $fileName = 'cycle_statement_' . $cycle->id . '.csv';
        $headers  = array(
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $BOM = "\xEF\xBB\xBF";
        $columns = array('التاريخ', 'البيان (الوصف)', 'مدين (مصروف)', 'دائن (إيراد)', 'الرصيد');

        $callback = function() use($cycle, $columns, $BOM) {
            $file = fopen('php://output', 'w');
            fwrite($file, $BOM);
            fputcsv($file, $columns);

            $balance     = 0;
            $totalDebit  = 0;
            $totalCredit = 0;

            foreach ($cycle->financialRecords as $record) {
                $debit  = 0;
                $credit = 0;

                if ($record->type === 'expense') {
                    $debit = $record->amount;
                    $totalDebit += $debit;
                    $balance -= $debit;
                } else {
                    $credit = $record->amount;
                    $totalCredit += $credit;
                    $balance += $credit;
                }

                fputcsv($file, array(
                    $record->record_date->format('Y-m-d'),
                    $record->description,
                    $debit  > 0 ? format_number($debit, 2) : '',
                    $credit > 0 ? format_number($credit, 2) : '',
                    format_number($balance, 2)
                ));
            }

            fputcsv($file, array('', '', '', '', ''));
            fputcsv($file, array('الإجمالي', '', format_number($totalDebit, 2), format_number($totalCredit, 2), format_number($balance, 2)));

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function syncSaleJournalEntry(FinancialRecord $record, array $data, Cycle $cycle): void
    {
        $entry = JournalEntry::where('reference_type', 'financial_record')
            ->where('reference_id', $record->id)
            ->first();

        $description = $data['description'] ?? ($record->description ?? 'مبيعات دورة');

        if (!$entry) {
            $entry = JournalEntry::create([
                'entry_number'   => JournalEntry::generateNumber(),
                'entry_date'     => $data['record_date'],
                'description'    => 'مبيعات دورة: ' . $description,
                'reference_type' => 'financial_record',
                'reference_id'   => $record->id,
            ]);
        }

        $entry->update([
            'entry_date'  => $data['record_date'],
            'description' => 'مبيعات دورة: ' . $description,
        ]);

        $entry->lines()->delete();

        if ($data['payment_type'] === 'cash') {
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_type'     => 'treasury',
                'account_id'       => $data['treasury_id'],
                'debit'            => $data['amount'],
                'credit'           => 0,
                'description'      => $description . ' - مبيعات كاش',
            ]);
        } else {
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_type'     => 'client',
                'account_id'       => $data['client_id'],
                'debit'            => $data['amount'],
                'credit'           => 0,
                'description'      => $description . ' - مبيعات آجلة',
            ]);
        }

        JournalEntryLine::create([
            'journal_entry_id' => $entry->id,
            'account_type'     => 'cycle',
            'account_id'       => $cycle->id,
            'debit'            => 0,
            'credit'           => $data['amount'],
            'description'      => 'إيرادات مبيعات',
        ]);
    }
}
