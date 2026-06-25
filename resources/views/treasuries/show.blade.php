@extends('components.master')
@section('title','كشف حساب الخزنة')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3><i class="fas fa-vault me-2 text-warning"></i>كشف حساب: {{ $treasury->name }}</h3>
        @if($treasury->notes)<p class="text-muted mb-0 small">{{ $treasury->notes }}</p>@endif
    </div>
    <div class="text-end">
        <div class="card px-4 py-2 border-{{ $treasury->balance >= 0 ? 'success' : 'danger' }}">
            <small class="text-muted">الرصيد الحالي</small>
            <h4 class="mb-0 text-{{ $treasury->balance >= 0 ? 'success' : 'danger' }}">
                {{ format_number($treasury->balance,2) }} ج.م
            </h4>
        </div>
    </div>
</div>

<div class="row mb-3 g-3">
    <div class="col-md-4">
        <div class="card text-center border-info py-2">
            <small class="text-muted">رصيد افتتاحي</small>
            <h5 class="text-info mb-0">{{ format_number($treasury->opening_balance,2) }}</h5>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-success py-2">
            <small class="text-muted">إجمالي الوارد (مدين)</small>
            <h5 class="text-success mb-0">{{ format_number($treasury->journalLines()->sum('debit'),2) }}</h5>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-danger py-2">
            <small class="text-muted">إجمالي الصادر (دائن)</small>
            <h5 class="text-danger mb-0">{{ format_number($treasury->journalLines()->sum('credit'),2) }}</h5>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header fw-bold">تفاصيل الحركات</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-striped mb-0 text-center">
                <thead class="table-dark">
                    <tr><th>التاريخ</th><th>البيان</th><th>المرجع</th><th>وارد (مدين)</th><th>صادر (دائن)</th><th>الرصيد</th></tr>
                </thead>
                <tbody>
                    <tr class="table-light">
                        <td>-</td><td class="text-start fw-bold">رصيد افتتاحي</td><td>-</td>
                        <td class="text-success">{{ format_number($treasury->opening_balance,2) }}</td>
                        <td>-</td>
                        <td class="fw-bold">{{ format_number($treasury->opening_balance,2) }}</td>
                    </tr>
                    @forelse($movements as $m)
                    <tr>
                        <td>{{ $m['date'] ? $m['date']->format('Y-m-d') : '-' }}</td>
                        <td class="text-start">{{ $m['description'] }}</td>
                        <td><small class="text-muted">{{ $m['reference'] }}</small></td>
                        <td class="text-success">{{ $m['debit'] > 0 ? format_number($m['debit'],2) : '-' }}</td>
                        <td class="text-danger">{{ $m['credit'] > 0 ? format_number($m['credit'],2) : '-' }}</td>
                        <td class="fw-bold text-{{ $m['balance'] >= 0 ? 'success' : 'danger' }}">{{ format_number($m['balance'],2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-3">لا توجد حركات مسجلة</td></tr>
                    @endforelse
                </tbody>
                <tfoot class="table-dark fw-bold">
                    <tr>
                        <td colspan="3" class="text-end">الرصيد النهائي</td>
                        <td class="text-success">{{ format_number($treasury->opening_balance + $treasury->journalLines()->sum('debit'),2) }}</td>
                        <td class="text-danger">{{ format_number($treasury->journalLines()->sum('credit'),2) }}</td>
                        <td class="text-{{ $treasury->balance >= 0 ? 'success' : 'danger' }}">{{ format_number($treasury->balance,2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">
    <a href="{{ route('treasuries.export', $treasury) }}" class="btn btn-success ms-2">
        <i class="fas fa-file-excel me-1"></i>تصدير إكسيل (Excel)
    </a>
    <a href="{{ route('journal-entries.create') }}" class="btn btn-primary ms-2"><i class="fas fa-exchange-alt me-1"></i>تحويل بين الخزائن</a>
</div>
@endsection
