@extends('components.master')
@section('title', 'عمليات الحساب')
@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1><i class="fa-solid fa-chart-bar me-2"></i>عمليات الحساب</h1>
        <p>عرض العمليات المالية لحساب: <strong>{{ $chartOfAccount->name }}</strong> ({{ $chartOfAccount->code }})</p>
    </div>
    <div>
        <a href="{{ route('chart-of-accounts.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-right me-1"></i>عودة لشجرة الحسابات
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>رقم القيد</th>
                        <th>التاريخ</th>
                        <th>البيان</th>
                        <th>مدين</th>
                        <th>دائن</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $counter = ($transactions->currentPage() - 1) * $transactions->perPage() + 1;
                    @endphp
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $counter++ }}</td>
                            <td>{{ $transaction->entry_number ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaction->entry_date)->format('Y-m-d') }}</td>
                            <td class="text-start">{{ $transaction->entry_description ?? $transaction->description ?? 'بدون بيان' }}</td>
                            <td class="text-success">{{ $transaction->debit > 0 ? format_number($transaction->debit, 2) : '-' }}</td>
                            <td class="text-danger">{{ $transaction->credit > 0 ? format_number($transaction->credit, 2) : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">لا توجد عمليات مسجلة لهذا الحساب</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
            <div class="card-footer">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
