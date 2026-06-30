<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\Shed;
use App\Models\PurchaseInvoice;
use App\Models\CycleDispensation;
use App\Models\FinancialRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartOfAccountController extends Controller
{
    public function index()
    {
        $accounts = ChartOfAccount::with(['children.shed', 'shed'])->roots()->get();
        $clients = \App\Models\Client::with('chartAccount')->orderBy('name')->get();
        $suppliers = \App\Models\Supplier::with('chartAccount')->orderBy('name')->get();

        $rows = [];
        $this->buildRows($accounts, $rows, 0, $clients, $suppliers);

        return view('chart-of-accounts.index', compact('rows'));
    }

    private function buildRows($accounts, &$rows, $level, $clients, $suppliers, $parentFullCode = '')
    {
        foreach ($accounts as $account) {
            $hasChildren = $account->children->isNotEmpty();
            $isDynamic = $account->code === '1120' || $account->code === '2110';
            $currentFullCode = $parentFullCode === '' ? $account->code : $parentFullCode . '.' . $account->code;

            $rows[] = [
                'type' => 'account',
                'account' => $account,
                'level' => $level,
                'hasChildren' => $hasChildren,
                'isDynamic' => $isDynamic,
                'full_code' => $currentFullCode,
            ];

            if ($account->code === '1120' && $clients->isNotEmpty()) {
                foreach ($clients as $client) {
                    if (!$client->chartAccount) {
                        $rows[] = [
                            'type' => 'client',
                            'account' => $client,
                            'level' => $level + 1,
                            'hasChildren' => false,
                            'isDynamic' => false,
                            'full_code' => $currentFullCode . '.' . $client->id,
                        ];
                    }
                }
            }

            if ($account->code === '2110' && $suppliers->isNotEmpty()) {
                foreach ($suppliers as $supplier) {
                    if (!$supplier->chartAccount) {
                        $rows[] = [
                            'type' => 'supplier',
                            'account' => $supplier,
                            'level' => $level + 1,
                            'hasChildren' => false,
                            'isDynamic' => false,
                            'full_code' => $currentFullCode . '.' . $supplier->id,
                        ];
                    }
                }
            }

            if ($hasChildren) {
                $this->buildRows($account->children, $rows, $level + 1, $clients, $suppliers, $currentFullCode);
            }
        }
    }

    public function create(Request $request)
    {
        $parents = ChartOfAccount::active()->roots()->orderBy('code')->get();
        $sheds = Shed::orderBy('name')->get();

        $entityName = old('name', $request->query('name', ''));
        $suggestedCode = old('code', $request->query('suggested_code', ''));
        $entityType = $request->query('linkable_type', '');
        $entityId = $request->query('linkable_id', '');

        if ($entityType && $entityId && !$entityName) {
            try {
                $entity = $entityType::find($entityId);
                if ($entity) {
                    $entityName = $entity->name;
                    if (!$suggestedCode) {
                        $suggestedCode = ($entityType === \App\Models\Supplier::class) ? '220' : '110';
                    }
                }
            } catch (\Exception $e) {
                $entityName = '';
                $suggestedCode = '';
            }
        }

        return view('chart-of-accounts.create', compact('parents', 'sheds', 'entityName', 'suggestedCode', 'entityType', 'entityId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:chart_of_accounts,code',
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'account_type' => 'required|in:asset_current,asset_fixed,liability_current,liability_long,equity,revenue,expense',
            'is_parent' => 'boolean',
            'is_active' => 'boolean',
            'opening_balance' => 'nullable|numeric|min:0',
            'shed_id' => 'nullable|exists:sheds,id',
            'linkable_type' => 'nullable|string',
            'linkable_id' => 'nullable|integer|unique:chart_of_accounts,linkable_id,NULL,id,linkable_type,' . $request->input('linkable_type'),
        ]);

        $validated['is_parent'] = $request->boolean('is_parent');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['opening_balance'] = $request->input('opening_balance', 0);
        $validated['linkable_type'] = $request->input('linkable_type');
        $validated['linkable_id'] = $request->input('linkable_id');

        DB::transaction(function () use ($validated, $request) {
            $account = ChartOfAccount::create($validated);

            if ($request->boolean('is_parent') && $account->parent_id) {
                $account->parent->update(['is_parent' => true]);
            }
        });

        return redirect()->route('chart-of-accounts.index')->with('success', 'تم إضافة الحساب بنجاح');
    }

    public function edit(ChartOfAccount $chartOfAccount)
    {
        $parents = ChartOfAccount::active()
            ->where('id', '!=', $chartOfAccount->id)
            ->roots()
            ->orderBy('code')
            ->get();
        $sheds = Shed::orderBy('name')->get();
        return view('chart-of-accounts.edit', compact('chartOfAccount', 'parents', 'sheds'));
    }

    public function update(Request $request, ChartOfAccount $chartOfAccount)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:chart_of_accounts,code,' . $chartOfAccount->id,
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:chart_of_accounts,id|not_in:' . $chartOfAccount->id,
            'account_type' => 'required|in:asset_current,asset_fixed,liability_current,liability_long,equity,revenue,expense',
            'is_parent' => 'boolean',
            'is_active' => 'boolean',
            'opening_balance' => 'nullable|numeric|min:0',
            'shed_id' => 'nullable|exists:sheds,id',
        ]);

        $validated['is_parent'] = $request->boolean('is_parent');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['opening_balance'] = $request->input('opening_balance', 0);

        DB::transaction(function () use ($validated, $request, $chartOfAccount) {
            $chartOfAccount->update($validated);

            if (!$request->boolean('is_parent') && $chartOfAccount->parent_id) {
                $siblings = $chartOfAccount->parent->children()->where('id', '!=', $chartOfAccount->id)->get();
                if ($siblings->isEmpty() || !$siblings->contains('is_parent', true)) {
                    $chartOfAccount->parent->update(['is_parent' => false]);
                }
            }
        });

        return redirect()->route('chart-of-accounts.index')->with('success', 'تم تعديل الحساب بنجاح');
    }

    public function destroy(ChartOfAccount $chartOfAccount)
    {
        $hasChildren = $chartOfAccount->children()->exists();
        if ($hasChildren) {
            return redirect()->route('chart-of-accounts.index')->with('error', 'لا يمكن حذف حساب يحتوي على بنود فرعية. قم بحذف البنود الفرعية أولاً.');
        }

        $chartOfAccount->delete();
        return redirect()->route('chart-of-accounts.index')->with('success', 'تم حذف الحساب بنجاح');
    }

    public function getChildren(Request $request, ChartOfAccount $account)
    {
        $children = $account->children()->active()->orderBy('code')->get();
        return response()->json($children);
    }

    public function getAccounts(Request $request)
    {
        $type = $request->query('type');
        $query = ChartOfAccount::active()->orderBy('code');

        if ($type) {
            $query->where('account_type', $type);
        }

        $accounts = $query->get()->map(function ($acc) {
            return [
                'id' => $acc->id,
                'text' => $acc->full_code . ' - ' . $acc->name,
                'code' => $acc->code,
                'name' => $acc->name,
                'account_type' => $acc->account_type,
                'is_parent' => $acc->is_parent,
            ];
        });

        return response()->json($accounts);
    }

    public function showTransactions(ChartOfAccount $chartOfAccount)
    {
        $query = \App\Models\JournalEntryLine::query()
            ->join('journal_entries', 'journal_entries.id', '=', 'journal_entry_lines.journal_entry_id')
            ->select(
                'journal_entry_lines.*',
                'journal_entries.entry_date',
                'journal_entries.description as entry_description',
                'journal_entries.entry_number',
                'journal_entries.reference_type',
                'journal_entries.reference_id'
            );

        $query->where(function($q) use ($chartOfAccount) {
            $q->where(function($sub) use ($chartOfAccount) {
                $sub->where('journal_entry_lines.account_type', 'chart_of_account')
                    ->where('journal_entry_lines.account_id', $chartOfAccount->id);
            });

            if ($chartOfAccount->linkable_type && $chartOfAccount->linkable_id) {
                $type = '';
                if ($chartOfAccount->linkable_type === \App\Models\Client::class || $chartOfAccount->linkable_type === 'Client') {
                    $type = 'client';
                } elseif ($chartOfAccount->linkable_type === \App\Models\Supplier::class || $chartOfAccount->linkable_type === 'Supplier') {
                    $type = 'supplier';
                }
                
                if ($type) {
                    $q->orWhere(function($sub) use ($type, $chartOfAccount) {
                        $sub->where('journal_entry_lines.account_type', $type)
                            ->where('journal_entry_lines.account_id', $chartOfAccount->linkable_id);
                    });
                }
            }
        });

        $transactions = $query->orderByDesc('journal_entries.entry_date')
            ->orderByDesc('journal_entry_lines.id')
            ->paginate(15);

        // جلب تفاصيل فواتير الشراء المرتبطة بالعمليات
        $purchaseInvoiceIds = $transactions->getCollection()
            ->where('reference_type', 'purchase_invoice')
            ->pluck('reference_id')
            ->unique()
            ->filter();

        $purchaseInvoices = [];
        if ($purchaseInvoiceIds->isNotEmpty()) {
            $purchaseInvoices = PurchaseInvoice::with([
                'supplier',
                'treasury',
                'items.item',
            ])->whereIn('id', $purchaseInvoiceIds)->get()->keyBy('id');
        }

        $saleInvoiceIds = $transactions->getCollection()
            ->where('reference_type', 'sale_invoice')
            ->pluck('reference_id')
            ->unique()
            ->filter();

        $saleInvoices = [];
        if ($saleInvoiceIds->isNotEmpty()) {
            $saleInvoices = \App\Models\SaleInvoice::with([
                'client',
                'treasury',
                'items.item',
                'cycle',
                'shed',
            ])->whereIn('id', $saleInvoiceIds)->get()->keyBy('id');
        }


        // جلب تفاصيل سجلات الصرف المالي (صرف علف/دواء على الدورات)
        $financialRecordIds = $transactions->getCollection()
            ->where('reference_type', 'financial_record')
            ->pluck('reference_id')
            ->unique()
            ->filter();

        $financialRecords = [];
        if ($financialRecordIds->isNotEmpty()) {
            $financialRecords = FinancialRecord::with([
                'cycle.shed',
                'shed',
            ])->whereIn('id', $financialRecordIds)->get()->keyBy('id');
        }

        // جلب عمليات الصرف على الدورات لكل صنف من فواتير الشراء
        $itemIds = collect($purchaseInvoices)->flatMap(fn($inv) => $inv->items->pluck('item_id'))->unique()->filter();
        $dispensationsByItem = [];
        if ($itemIds->isNotEmpty()) {
            $dispensations = CycleDispensation::with(['cycle.shed', 'shed', 'item'])
                ->whereIn('item_id', $itemIds)
                ->orderBy('dispensation_date')
                ->get();
            foreach ($dispensations as $d) {
                $dispensationsByItem[$d->item_id][] = $d;
            }
        }

        return view('chart-of-accounts.transactions', compact(
            'chartOfAccount',
            'transactions',
            'purchaseInvoices',
            'saleInvoices',
            'financialRecords',
            'dispensationsByItem'
        ));
    }
}