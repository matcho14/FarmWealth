@extends('components.master')
@section('title','تفاصيل القيد')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3><i class="fas fa-exchange-alt me-2 text-primary"></i>قيد رقم: {{ $journalEntry->entry_number }}</h3>
        <p class="text-muted mb-0">{{ $journalEntry->entry_date->format('Y-m-d') }} | {{ $journalEntry->description }}</p>
    </div>
    <span class="badge bg-{{ $journalEntry->reference_type === 'manual' ? 'secondary' : 'success' }} fs-6">
        {{ $journalEntry->reference_type === 'manual' ? 'يدوي' : 'فاتورة بيع' }}
    </span>
</div>

<div class="card">
    <div class="card-header fw-bold">سطور القيد</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0 text-center">
                <thead class="table-dark">
                    <tr><th>#</th><th>نوع الحساب</th><th>اسم الحساب</th><th>البيان</th><th>مدين</th><th>دائن</th></tr>
                </thead>
                <tbody>
                    @foreach($journalEntry->lines as $i => $line)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>
                            @php
                                $accountLabels = [
                                    'supplier' => ['primary', 'مورد'],
                                    'client' => ['info', 'عميل'],
                                    'treasury' => ['success', 'خزنة'],
                                    'cycle' => ['warning', 'دورة/مبيعات'],
                                    'sales' => ['danger', 'مبيعات'],
                                    'chart_of_account' => ['dark', 'حساب'],
                                ];
                                $accountLabel = $accountLabels[$line->account_type] ?? ['secondary', $line->account_type];
                            @endphp
                            <span class="badge bg-{{ $accountLabel[0] }} text-{{ $accountLabel[0] === 'warning' ? 'dark' : 'white' }}">
                                {{ $accountLabel[1] }}
                            </span>
                        </td>
                        <td class="fw-bold">{{ $line->account_name }}</td>
                        <td class="text-muted text-start">{{ $line->description ?? '-' }}</td>
                        <td class="text-success fw-bold">{{ $line->debit  > 0 ? format_number($line->debit,2)  : '-' }}</td>
                        <td class="text-danger fw-bold">{{ $line->credit > 0 ? format_number($line->credit,2) : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="4" class="text-end">الإجمالي</td>
                        <td class="text-success">{{ format_number($journalEntry->lines->sum('debit'),2) }}</td>
                        <td class="text-danger">{{ format_number($journalEntry->lines->sum('credit'),2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@if($journalEntry->notes)
<div class="alert alert-light border mt-3"><strong>ملاحظات:</strong> {{ $journalEntry->notes }}</div>
@endif

<div class="mt-3">
    <a href="{{ route('journal-entries.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>العودة</a>
</div>
@endsection
