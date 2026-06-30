<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\JournalEntryLine;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('name')->get();
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        $parents = \App\Models\ChartOfAccount::active()
            ->with('children:chart_of_accounts.id,chart_of_accounts.code,chart_of_accounts.name,chart_of_accounts.parent_id')
            ->get();

        return view('clients.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'phone'           => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            'address'         => 'nullable|string',
            'opening_balance' => 'nullable|numeric|min:0',
            'notes'           => 'nullable|string',
        ]);

        $client = \App\Models\Client::create($data);

        if ($request->filled('account_code')) {
            \App\Models\ChartOfAccount::create([
                'code'            => $request->account_code,
                'name'            => $client->name,
                'parent_id'       => $request->parent_id,
                'account_type'    => 'asset_current',
                'is_parent'       => false,
                'is_active'       => true,
                'opening_balance' => $client->opening_balance,
                'linkable_type'   => \App\Models\Client::class,
                'linkable_id'     => $client->id,
            ]);
        }

        return redirect()->route('clients.index')->with('success', 'تم إضافة العميل بنجاح');
    }

    public function show(Client $client)
    {
        $lines = JournalEntryLine::where('account_type', 'client')
            ->where('account_id', $client->id)
            ->with('journalEntry')
            ->orderBy('created_at', 'asc')
            ->get();

        $runningBalance = $client->opening_balance;
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

        return view('clients.show', compact('client', 'movements'));
    }

    public function exportStatement(Client $client)
    {
        $lines = JournalEntryLine::where('account_type', 'client')
            ->where('account_id', $client->id)
            ->with('journalEntry')
            ->orderBy('created_at', 'asc')
            ->get();

        $filename = "Client_Statement_" . str_replace(' ', '_', $client->name) . "_" . date('Y-m-d') . ".csv";
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0",
        ];

        $callback = function () use ($client, $lines) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM for Excel

            fputcsv($file, ['كشف حساب عميل:', $client->name]);
            fputcsv($file, ['التاريخ:', date('Y-m-d')]);
            fputcsv($file, ['الرصيد الافتتاحي:', $client->opening_balance]);
            fputcsv($file, []);

            fputcsv($file, ['التاريخ', 'البيان', 'رقم المرجع', 'مدين (مبيعات)', 'دائن (مقبوضات)', 'الرصيد']);

            $runningBalance = $client->opening_balance;
            foreach ($lines as $line) {
                $runningBalance += $line->debit - $line->credit;
                fputcsv($file, [
                    $line->journalEntry?->entry_date?->format('Y-m-d'),
                    $line->description ?? $line->journalEntry?->description,
                    $line->journalEntry?->entry_number,
                    $line->debit,
                    $line->credit,
                    $runningBalance,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'phone'           => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            'address'         => 'nullable|string',
            'opening_balance' => 'nullable|numeric|min:0',
            'notes'           => 'nullable|string',
        ]);
        $client->update($data);
        return redirect()->route('clients.index')->with('success', 'تم تحديث العميل بنجاح');
    }

    public function destroy(Client $client)
    {
        if ($client->chartAccount) {
            $client->chartAccount->delete();
        }
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'تم حذف العميل');
    }
}