@extends('components.master')
@section('title', 'تقرير المصاريف حسب الحساب والعنبر')
@section('content')
<div class="page-header">
    <div>
        <h1><i class="fa-solid fa-magnifying-glass-chart me-2"></i>تقرير المصاريف</h1>
        <p>تقرير مفصل للمصاريف حسب الحساب المحاسبي والعنبر والدورة</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('expense-report') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">من تاريخ</label>
                <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">إلى تاريخ</label>
                <input type="date" name="to_date" class="form-control" value="{{ $toDate }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">العنبر</label>
                <select name="shed_id" id="shedFilter" class="form-select select2">
                    <option value="">-- الكل --</option>
                    @foreach($sheds as $shed)
                        <option value="{{ $shed->id }}" {{ $shedId == $shed->id ? 'selected' : '' }}>{{ $shed->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">الدورة</label>
                <select name="cycle_id" id="cycleFilter" class="form-select select2">
                    <option value="">-- الكل --</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">حساب المصروف</label>
                <select name="chart_of_account_id" class="form-select select2">
                    <option value="">-- الكل --</option>
                    @foreach($chartAccounts as $account)
                        <option value="{{ $account->id }}" {{ $chartAccountId == $account->id ? 'selected' : '' }}>
                            {{ $account->full_code }} - {{ $account->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12 d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-filter me-1"></i>تصفية
                </button>
                <a href="{{ route('expense-report') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-rotate-right me-1"></i>إعادة تعيين
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">نتائج البحث</h5>
        <span class="badge bg-primary fs-6">الإجمالي: {{ format_number($grandTotal, 2) }} </span>
    </div>
    <div class="card-body p-0">
        @if($records->isEmpty())
            <div class="alert alert-info m-3">لا توجد مصاريف مطابقة للبحث.</div>
        @else
        <div class="table-responsive">
            <table class="table table-hover mb-0 text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>التاريخ</th>
                        <th>الوصف</th>
                        <th>الحساب المحاسبي</th>
                        <th>العنبر</th>
                        <th>الدورة</th>
                        <th>الخزينة</th>
                        <th>المبلغ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $record)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $record->record_date?->format('Y-m-d') }}</td>
                        <td class="text-start">{{ $record->description }}</td>
                        <td>
                            @if($record->chartOfAccount)
                                <span class="badge bg-secondary">{{ $record->chartOfAccount->full_code }} - {{ $record->chartOfAccount->name }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $record->cycle?->shed->name ?? '-' }}</td>
                        <td>
                            @if($record->cycle)
                                <a href="{{ route('cycles.show', $record->cycle) }}">
                                    دورة #{{ $record->cycle->id }} ({{ $record->cycle->start_date?->format('Y-m-d') }})
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $record->treasury->name ?? '-' }}</td>
                        <td class="fw-bold text-danger">{{ format_number($record->amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $records->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

@endsection

@section('scripts')
<script>
const cyclesByShed = @json($cyclesByShed ?? []);
const currentShedId = '{{ $shedId }}';
const currentCycleId = '{{ $cycleId }}';

function loadCycles(shedId, selectedId) {
    const cycleFilter = $('#cycleFilter');
    cycleFilter.html('<option value="">-- الكل --</option>');
    if (!shedId) return;

    const list = cyclesByShed[shedId] || [];
    list.forEach(function(c) {
        const start = c.start_date || '';
        const end = c.end_date && c.end_date !== '0000-00-00' ? c.end_date : '';
        const statusBadge = c.status === 'active' ? ' [نشطة]' : ' [مغلقة]';
        const dateRange = end ? start + ' إلى ' + end : start;
        cycleFilter.append('<option value="' + c.id + '" ' + (c.id == selectedId ? 'selected' : '') + '>دورة #' + c.id + ' | ' + dateRange + statusBadge + '</option>');
    });
}

$(document).on('change', '#shedFilter', function() {
    loadCycles($(this).val(), '');
});

if (currentShedId) {
    loadCycles(currentShedId, currentCycleId);
}
</script>
@endsection

