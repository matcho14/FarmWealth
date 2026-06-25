<?php

namespace App\Http\Controllers;

use App\Models\Cycle;
use App\Models\FinancialRecord;
use App\Models\Treasury;
use App\Models\Client;
use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use App\Models\Shed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialRecordController extends Controller
{
    /**
      * عرض نموذج إضافة سجل مالي جديد
      */
    public function create(Cycle $cycle)
    {
        if ($cycle->status !== 'active') {
            return redirect()->route('cycles.show', $cycle)->with('error', 'لا يمكن إضافة سجلات لدورة مغلقة');
        }

        $treasuries = Treasury::orderBy('name')->get();
        $chartAccounts = ChartOfAccount::active()->where('account_type', 'expense')->with('parent')->orderBy('code')->get();
        return view('financial-records.create', compact('cycle', 'treasuries', 'chartAccounts'));
    }

    /**
      * حفظ سجل مالي جديد
      */
    public function store(Request $request, Cycle $cycle)
    {
        $validated = $request->validate([
            'type'        => 'required|in:expense,revenue',
            'amount'      => 'required|numeric|min:0.01',
            'description' => 'required|string|max:500',
            'record_date' => 'required|date|before_or_equal:today',
            'treasury_id' => 'required|exists:treasuries,id',
            'chart_of_account_id' => 'nullable|exists:chart_of_accounts,id',
            'floor_number' => 'nullable|integer|min:1',
        ]);

        $validated['cycle_id'] = $cycle->id;
        $validated['shed_id'] = $cycle->shed_id;

        DB::transaction(function () use ($validated, $cycle) {
            $record = FinancialRecord::create($validated);
            $this->syncJournalEntry($record, $validated);
        });

        return redirect()->route('cycles.show', $cycle)->with('success', 'تم إضافة السجل المالي وتأثيره على الخزينة والقيد المحاسبي بنجاح');
    }

    public function show(FinancialRecord $financialRecord)
    {
        $financialRecord->load(['cycle','shed','treasury','client','chartOfAccount']);
        return view('financial-records.show', compact('financialRecord'));
    }

    /**
      * عرض نموذج تعديل سجل مالي
      */
    public function edit(FinancialRecord $financialRecord)
    {
        $sheds = Shed::orderBy('name')->get();
        $currentCycle = $financialRecord->cycle;
        $currentShed = $currentCycle->shed ?? null;
        $cycles = $currentShed ? Cycle::where('shed_id', $currentShed->id)->orderByDesc('start_date')->get() : collect();
        $treasuries = Treasury::orderBy('name')->get();
        $chartAccounts = ChartOfAccount::active()->where('account_type', 'expense')->with('parent')->orderBy('code')->get();

        return view('financial-records.edit', compact('financialRecord', 'sheds', 'currentShed', 'cycles', 'currentCycle', 'treasuries', 'chartAccounts'));
    }

    /**
      * تحديث سجل مالي
      */
    public function update(Request $request, FinancialRecord $financialRecord)
    {
        $validated = $request->validate([
            'type' => 'required|in:expense,revenue',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:500',
            'record_date' => 'required|date|before_or_equal:today',
            'treasury_id' => 'required|exists:treasuries,id',
            'chart_of_account_id' => 'nullable|exists:chart_of_accounts,id',
            'floor_number' => 'nullable|integer|min:1',
            'cycle_id' => 'required|exists:cycles,id',
        ]);

        $newCycle = Cycle::findOrFail($validated['cycle_id']);
        $oldCycleId = $financialRecord->cycle_id;
        $cycleChanged = $oldCycleId != $newCycle->id;

        DB::transaction(function () use ($validated, $financialRecord, $newCycle, $cycleChanged, $oldCycleId) {
            $validated['shed_id'] = $newCycle->shed_id;
            $validated['cycle_id'] = $newCycle->id;

            $financialRecord->update($validated);

            if ($cycleChanged) {
                JournalEntryLine::where('journal_entry_id', function($q) use ($financialRecord) {
                    $q->select('id')->from('journal_entries')->where('reference_type', 'financial_record')->where('reference_id', $financialRecord->id);
                })->where('account_type', 'cycle')->update(['account_id' => $newCycle->id]);

                JournalEntry::where('reference_type', 'financial_record')->where('reference_id', $financialRecord->id)->update([
                    'description' => ($validated['type'] === 'expense' ? 'مصروف دورة: ' : 'إيراد دورة: ') . $validated['description'],
                ]);
            } else {
                $this->syncJournalEntry($financialRecord, $validated);
            }
        });

        $redirectCycle = $cycleChanged ? $newCycle : $financialRecord->cycle;
        return redirect()->route('cycles.show', $redirectCycle)->with('success', 'تم تحديث السجل المالي والقيد المرتبط به بنجاح');
    }

    /**
      * حذف سجل مالي
      */
    public function destroy(FinancialRecord $financialRecord)
    {
        $cycle = $financialRecord->cycle;

        DB::transaction(function () use ($financialRecord) {
            JournalEntry::where('reference_type', 'financial_record')
                ->where('reference_id', $financialRecord->id)
                ->delete();

            $financialRecord->delete();
        });

        return redirect()->route('cycles.show', $cycle)->with('success', 'تم حذف السجل المالي والقيود المرتبطة به بنجاح');
    }

    /**
      * عرض التقرير السنوي للعنبر
      */
    public function annualReport(Request $request)
    {
        $year = $request->query('year', now()->year);
        $month = $request->query('month');

        $cycles = Cycle::whereYear('start_date', $year)
            ->where('status', 'completed')
            ->with('financialRecords')
            ->get();

        if ($month) {
            $cycles = $cycles->filter(function ($cycle) use ($month) {
                return $cycle->start_date->month == $month;
            });
        }

        $totalExpenses = 0;
        $totalRevenues = 0;
        $netProfit = 0;

        foreach ($cycles as $cycle) {
            $totalExpenses += $cycle->total_expenses;
            $totalRevenues += $cycle->total_revenues;
            $netProfit += $cycle->net_profit;
        }

        return view('financial-records.annual-report', compact('cycles', 'year', 'month', 'totalExpenses', 'totalRevenues', 'netProfit'));
    }

    /**
      * تقرير المصاريف حسب الحساب والعنبر والدورة
      */
    public function expenseReport(Request $request)
    {
        $sheds = Shed::orderBy('name')->get();
        $chartAccounts = ChartOfAccount::active()->where('account_type', 'expense')->with('parent')->orderBy('code')->get();
        
        $shedId = $request->query('shed_id');
        $cycleId = $request->query('cycle_id');
        $chartAccountId = $request->query('chart_of_account_id');
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $query = FinancialRecord::where('type', 'expense')
            ->with(['cycle.shed', 'chartOfAccount', 'treasury']);

        if ($shedId) {
            $query->whereHas('cycle', function($q) use ($shedId) {
                $q->where('shed_id', $shedId);
            });
        }

        if ($cycleId) {
            $query->where('cycle_id', $cycleId);
        }

        if ($chartAccountId) {
            $query->where('chart_of_account_id', $chartAccountId);
        }

        if ($fromDate) {
            $query->whereDate('record_date', '>=', $fromDate);
        }

        if ($toDate) {
            $query->whereDate('record_date', '<=', $toDate);
        }

        $records = $query->orderByDesc('record_date')->paginate(50);

        $cyclesList = Cycle::orderByDesc('start_date')->get(['id', 'start_date', 'end_date', 'status', 'shed_id']);
        $cyclesByShed = $cyclesList->groupBy('shed_id')->map(function ($items) {
            return $items->map(function ($c) {
                return [
                    'id' => $c->id,
                    'start_date' => $c->start_date ? \Carbon\Carbon::parse($c->start_date)->toDateString() : '',
                    'end_date' => $c->end_date ? \Carbon\Carbon::parse($c->end_date)->toDateString() : '',
                    'status' => $c->status,
                ];
            })->values()->all();
        })->all();

        // Aggregated totals by chart account
        $byAccount = $query->get()->groupBy('chart_of_account_id')->map(function($items) {
            $accountId = $items->first()->chart_of_account_id;
            $account = $items->first()->chartOfAccount;
            return [
                'account_id' => $accountId,
                'account_name' => $account ? $account->full_code . ' - ' . $account->name : 'بدون حساب',
                'total' => $items->sum('amount'),
                'items' => $items,
            ];
        });

        // Aggregated totals by shed
        $byShed = $query->get()->groupBy(function($item) {
            return $item->cycle->shed_id ?? 0;
        })->map(function($items) {
            $shedId = $items->first()->cycle->shed_id ?? 0;
            $shed = $items->first()->cycle->shed;
            return [
                'shed_id' => $shedId,
                'shed_name' => $shed ? $shed->name : 'بدون عنبر',
                'total' => $items->sum('amount'),
            ];
        });

        // Aggregated totals by cycle
        $byCycle = $query->get()->groupBy('cycle_id')->map(function($items) {
            $cycle = $items->first()->cycle;
            return [
                'cycle_id' => $cycle ? $cycle->id : 0,
                'cycle_label' => $cycle ? 'دورة #' . $cycle->id . ' (' . $cycle->start_date->format('Y-m-d') . ')' : 'بدون دورة',
                'total' => $items->sum('amount'),
            ];
        });

        $grandTotal = $records->sum('amount');

        return view('financial-records.expense-report', compact(
            'records', 'sheds', 'chartAccounts', 'grandTotal',
            'shedId', 'cycleId', 'chartAccountId', 'fromDate', 'toDate',
            'byAccount', 'byShed', 'byCycle', 'cyclesByShed'
        ));
    }

    /**
      * جلب الدورات حسب العنبر (لأجل التقرير)
      */
    public function getCyclesByShed(Request $request)
    {
        $shedId = $request->query('shed_id');
        $cycles = Cycle::where('shed_id', $shedId)
            ->orderByDesc('start_date')
            ->get(['id', 'start_date', 'end_date', 'status']);
        
        return response()->json($cycles);
    }

    private function syncJournalEntry(FinancialRecord $record, array $data): void
    {
        $cycle = $record->cycle;

        $entry = JournalEntry::where('reference_type', 'financial_record')
            ->where('reference_id', $record->id)
            ->first();

        if (!$entry) {
            $entry = JournalEntry::create([
                'entry_number'   => JournalEntry::generateNumber(),
                'entry_date'     => $data['record_date'],
                'description'    => ($data['type'] === 'expense' ? 'مصروف دورة: ' : 'إيراد دورة: ') . $data['description'],
                'reference_type' => 'financial_record',
                'reference_id'   => $record->id,
            ]);
        }

        $entry->update([
            'entry_date'  => $data['record_date'],
            'description' => ($data['type'] === 'expense' ? 'مصروف دورة: ' : 'إيراد دورة: ') . $data['description'],
        ]);

        $entry->lines()->delete();

        $accountType = $data['chart_of_account_id'] ? 'chart_of_account' : 'cycle';
        $accountId = $data['chart_of_account_id'] ?? $cycle->id;

        if ($data['type'] === 'expense') {
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_type'     => $accountType,
                'account_id'       => $accountId,
                'debit'            => $data['amount'],
                'credit'           => 0,
                'description'      => $data['description'],
            ]);
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_type'     => 'treasury',
                'account_id'       => $data['treasury_id'],
                'debit'            => 0,
                'credit'           => $data['amount'],
                'description'      => $data['description'],
            ]);
        } else {
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_type'     => 'treasury',
                'account_id'       => $data['treasury_id'],
                'debit'            => $data['amount'],
                'credit'           => 0,
                'description'      => $data['description'],
            ]);
            JournalEntryLine::create([
                'journal_entry_id' => $entry->id,
                'account_type'     => $accountType,
                'account_id'       => $accountId,
                'debit'            => 0,
                'credit'           => $data['amount'],
                'description'      => $data['description'],
            ]);
        }
    }
}
