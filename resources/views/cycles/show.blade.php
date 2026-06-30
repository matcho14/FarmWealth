@extends('components.master')

@section('title', 'تفاصيل الدورة')

@section('content')
@php
    $user = auth()->user();
@endphp
<div class="row mb-4">
    <div class="col-md-8">
        <h2>دورة #{{ $cycle->id }} - {{ $cycle->shed->name }}</h2>
        <p class="text-muted">من {{ $cycle->start_date->format('Y-m-d') }} @if($cycle->end_date) إلى {{ $cycle->end_date->format('Y-m-d') }} @endif</p>
    </div>
    <div class="col-md-4 text-end">
        <span class="badge bg-{{ $cycle->status === 'active' ? 'success' : 'secondary' }} fs-6">
            {{ $cycle->status === 'active' ? 'نشطة' : 'مغلقة' }}
        </span>
    </div>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory" type="button" role="tab" style="color: black !important; font-weight: bold;">
            إدارة المخزون
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="floors-tab" data-bs-toggle="tab" data-bs-target="#floors" type="button" role="tab" style="color: black !important; font-weight: bold;">
            <i class="fas fa-layer-group me-1"></i>
            تفاصيل الأدوار
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="mortality-tab" data-bs-toggle="tab" data-bs-target="#mortality-log" type="button" role="tab" style="color: black !important; font-weight: bold;">
            <i class="fas fa-skull-crossbones me-1"></i>
            سجل النافق ({{ $cycle->mortalityRecords->count() }})
        </button>
    </li>
    @if($user && $user->hasPermission('financial-prices'))
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="financial-tab" data-bs-toggle="tab" data-bs-target="#financial" type="button" role="tab" style="color: black !important; font-weight: bold;">
            السجلات المالية ({{ count($cycle->financialRecords) }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="statement-tab" data-bs-toggle="tab" data-bs-target="#statement" type="button" role="tab" style="color: black !important; font-weight: bold;">
            كشف حساب الدورة
        </button>
    </li>
    @endif
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="dispensations-tab" data-bs-toggle="tab" data-bs-target="#dispensations" type="button" role="tab" style="color: black !important; font-weight: bold;">
            <i class="fas fa-hand-holding-medical me-1"></i>
            سجل الصرف ({{ $cycle->dispensations->count() }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="feed-tab" data-bs-toggle="tab" data-bs-target="#feed-log" type="button" role="tab" style="color: black !important; font-weight: bold;">
            <i class="fas fa-seedling me-1"></i>
            تقرير العلف
        </button>
    </li>
</ul>

<!-- Inventory Summary -->
<div class="row mb-4">
    <div class="col-md mb-3 mb-md-0">
        <div class="card text-center h-100">
            <div class="card-body">
                <h6 class="card-title text-muted">الكتاكيت الأولي</h6>
                <h3 class="text-info">{{ $cycle->initial_chicks }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md mb-3 mb-md-0">
        <div class="card text-center h-100">
            <div class="card-body">
                <h6 class="card-title text-muted">النافق الكلي</h6>
                <h3 class="text-warning">{{ $cycle->mortality_count }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md mb-3 mb-md-0">
        <div class="card text-center h-100">
            <div class="card-body">
                <h6 class="card-title text-muted">المباع</h6>
                <h3 class="text-primary">{{ $cycle->sold_chicks }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md mb-3 mb-md-0">
        <div class="card text-center h-100">
            <div class="card-body">
                <h6 class="card-title text-muted">المتبقي</h6>
                <h3 class="text-success">{{ $cycle->expected_remaining }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md mb-3 mb-md-0">
        <div class="card text-center h-100">
            <div class="card-body">
                <h6 class="card-title text-muted">متوسط الوزن العام</h6>
                <h3 class="text-secondary">{{ format_number($cycle->average_weight, 2) }} <small class="fs-6">كجم</small></h3>
            </div>
        </div>
    </div>
    <div class="col-md mb-3 mb-md-0">
        <div class="card text-center h-100">
            <div class="card-body">
                <h6 class="card-title text-muted">استهلاك العلف</h6>
                <h3 class="text-primary">{{ format_number($cycle->total_feed_consumed, 2) }} <small class="fs-6">شيكارة</small></h3>
            </div>
        </div>
    </div>
</div>

<!-- Financial Summary - يظهر فقط لمن يملك صلاحية رؤية الأسعار -->
@if($user && $user->hasPermission('financial-prices'))
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-title text-muted">المصروفات</h6>
                <h3 class="text-danger">{{ format_number($cycle->total_expenses, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-title text-muted">الإيرادات</h6>
                <h3 class="text-success">{{ format_number($cycle->total_revenues, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-title text-muted">صافي الحاصل</h6>
                <h3 class="text-{{ $cycle->net_profit > 0 ? 'success' : 'danger' }}">{{ format_number($cycle->net_profit, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-title text-muted">الهامش</h6>
                @if($cycle->total_revenues > 0)
                    <h3 class="text-info">{{ format_number(($cycle->net_profit / $cycle->total_revenues) * 100, 1) }}%</h3>
                @else
                    <h3 class="text-muted">-</h3>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<div class="tab-content">
    <!-- Inventory Management Tab -->
    <div class="tab-pane fade show active" id="inventory" role="tabpanel">
        @if ($cycle->status === 'active')
            <div class="row mb-3">
                <div class="col-md-6">
                    <a href="{{ route('cycles.editMortality', $cycle) }}" class="btn btn-warning">
                        <i class="fas fa-cross me-2"></i>إضافة نافق
                    </a>
                    <a href="{{ route('cycles.createSales', $cycle) }}" class="btn btn-primary ms-2">
                        <i class="fas fa-shopping-cart me-2"></i>إضافة مبيعات
                    </a>
                    <a href="{{ route('inventory.dispense.create', $cycle->id) }}" class="btn btn-success ms-2">
                        <i class="fas fa-hand-holding-medical me-2"></i>صرف دواء/علف
                    </a>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('cycles.closeCycleForm', $cycle) }}" class="btn btn-danger">
                        <i class="fas fa-times-circle me-2"></i>إغلاق الدورة
                    </a>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">ملخص المخزون</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <p>الكتاكيت الأولي: <strong>{{ $cycle->initial_chicks }}</strong></p>
                        <p>النافق المسجل: <strong>{{ $cycle->mortality_count }}</strong></p>
                        <p>الكتاكيت المتبقي: <strong>{{ $cycle->expected_remaining }}</strong></p>
                    </div>
                    @if ($cycle->sold_chicks > 0)
                        <div class="col-md-6 mb-3">
                            <p>إجمالي الكتاكيت المباعة: <strong>{{ $cycle->sold_chicks }}</strong></p>
                            <p>إجمالي الوزن المسجل: <strong>{{ format_number($cycle->total_weight, 2) }} كيلو</strong></p>
                            <p>متوسط وزن الفرخة العام: <strong>{{ format_number($cycle->average_weight, 3) }} كيلو</strong></p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Floors Details Tab -->
    <div class="tab-pane fade" id="floors" role="tabpanel">
        @php
            $floorChicks     = $cycle->floor_chicks ?? [];
            $mortalityByFloor = $cycle->mortalityByFloor;
            $floors          = $cycle->shed->floors ?? 1;
        @endphp

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-layer-group me-2"></i>تفاصيل الأدوار</h5>
                <span class="badge bg-secondary">{{ $floors }} أدوار</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0 text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>الدور</th>
                                <th>الكتاكيت الأولي</th>
                                <th>النافق</th>
                                <th>المتبقي</th>
                                <th>نسبة النفوق</th>
                                <th>استهلاك العلف (شيكارة)</th>
                                @if($user && $user->hasPermission('financial-prices'))
                                <th>مصروفات الأصناف (علف/دواء)</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @php $feedConsumedByFloor = $cycle->feed_consumed_by_floor; @endphp
                            @for($floor = 1; $floor <= $floors; $floor++)
                                @php
                                    $initial   = (int)($floorChicks[$floor] ?? 0);
                                    $dead      = (int)($mortalityByFloor[$floor] ?? 0);
                                    $remaining = $initial - $dead;
                                    $deathRate = $initial > 0 ? ($dead / $initial) * 100 : 0;
                                    $feedQty   = $feedConsumedByFloor[$floor] ?? 0;
                                @endphp
                                <tr>
                                    <td class="fw-bold">
                                        <span class="badge bg-secondary fs-6">الدور {{ $floor }}</span>
                                    </td>
                                    <td class="text-info fw-bold">{{ format_number($initial) }}</td>
                                    <td class="text-danger fw-bold">{{ format_number($dead) }}</td>
                                    <td class="text-success fw-bold">{{ format_number($remaining) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $deathRate > 5 ? 'danger' : ($deathRate > 2 ? 'warning text-dark' : 'success') }}">
                                            {{ format_number($deathRate, 1) }}%
                                        </span>
                                    </td>
                                    <td class="text-warning fw-bold text-dark">{{ format_number($feedQty, 2) }}</td>
                                    @if($user && $user->hasPermission('financial-prices'))
                                    <td class="text-primary fw-bold">
                                        {{ format_number($cycle->expenses_by_floor[$floor] ?? 0, 2) }} ج.م
                                    </td>
                                    @endif
                                </tr>
                            @endfor
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td>الإجمالي</td>
                                <td class="text-info">{{ format_number($cycle->initial_chicks) }}</td>
                                <td class="text-danger">{{ format_number($cycle->mortality_count) }}</td>
                                <td class="text-success">{{ format_number($cycle->expected_remaining + $cycle->sold_chicks) }}</td>
                                @php
                                    $totalRate = $cycle->initial_chicks > 0 ? ($cycle->mortality_count / $cycle->initial_chicks) * 100 : 0;
                                @endphp
                                <td>
                                    <span class="badge bg-{{ $totalRate > 5 ? 'danger' : ($totalRate > 2 ? 'warning text-dark' : 'success') }}">
                                        {{ format_number($totalRate, 1) }}%
                                    </span>
                                </td>
                                <td class="text-warning text-dark">{{ format_number($cycle->total_feed_consumed, 2) }}</td>
                                @if($user && $user->hasPermission('financial-prices'))
                                <td class="text-primary">{{ format_number(array_sum($cycle->expenses_by_floor), 2) }} ج.م</td>
                                @endif
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Mortality Log Tab -->
    <div class="tab-pane fade" id="mortality-log" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-skull-crossbones me-2 text-warning"></i>سجل النافق التفصيلي</h5>
            </div>
            <div class="card-body p-0">
                @if($cycle->mortalityRecords->isEmpty())
                    <div class="alert alert-info m-3">
                        <i class="fas fa-info-circle me-2"></i>لا توجد سجلات نافق مسجلة بعد
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-secondary">
                                <tr class="text-center">
                                    <th>التاريخ</th>
                                    <th>الدور</th>
                                    <th>العدد</th>
                                    <th>ملاحظات</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cycle->mortalityRecords as $mr)
                                    <tr class="text-center">
                                        <td>{{ $mr->record_date->format('Y-m-d') }}</td>
                                        <td>
                                            <span class="badge bg-secondary">الدور {{ $mr->floor_number }}</span>
                                        </td>
                                        <td class="text-danger fw-bold">{{ $mr->count }}</td>
                                        <td class="text-start text-muted small">{{ $mr->notes ?? '-' }}</td>
                                        <td class="text-nowrap">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('cycles.mortality.show', [$cycle, $mr]) }}" class="btn btn-info text-white" title="عرض">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('cycles.editMortalityRecord', [$cycle, $mr]) }}" class="btn btn-warning" title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('cycles.destroyMortalityRecord', [$cycle, $mr]) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف سجل النافق؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="حذف">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light fw-bold">
                                <tr class="text-center">
                                    <td colspan="2">إجمالي النافق</td>
                                    <td class="text-danger">{{ $cycle->mortalityRecords->sum('count') }}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Financial Records Tab - يظهر فقط لمن يملك صلاحية الأسعار -->
    @if($user && $user->hasPermission('financial-prices'))
    <div class="tab-pane fade" id="financial" role="tabpanel">
        <div class="mb-3">
            @if ($cycle->status === 'active')
                <a href="{{ route('cycles.createRecord', $cycle) }}" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>إضافة سجل مالي
                </a>
            @endif
        </div>

        @if ($cycle->financialRecords->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>لا توجد سجلات مالية حالياً
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>التاريخ</th>
                            <th>نوع الدفع</th>
                            <th>العميل</th>
                            <th>الوصف</th>
                            <th>الكمية/الوزن</th>
                            <th>متوسط الوزن</th>
                            <th>المبلغ</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cycle->financialRecords as $record)
                            <tr>
                                <td>{{ $record->record_date->format('Y-m-d') }}</td>
                                <td>
                                    @if($record->type === 'revenue')
                                        <span class="badge bg-{{ $record->payment_type === 'cash' ? 'info' : 'warning text-dark' }}">
                                            {{ $record->payment_type === 'cash' ? 'كاش' : 'اجل' }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($record->client)
                                        <span class="text-primary">{{ $record->client->name }}</span>
                                    @endif
                                </td>
                                <td>{{ $record->description }}</td>
                                <td>
                                    @if($record->weight)
                                        {{ $record->quantity }} فرخة / {{ format_number($record->weight, 2) }} كجم
                                    @endif
                                </td>
                                <td>
                                    @if($record->average_weight)
                                        {{ format_number($record->average_weight, 3) }} كجم
                                    @endif
                                </td>
                                <td class="text-{{ $record->type === 'expense' ? 'danger' : 'success' }}">
                                    {{ $record->type === 'expense' ? '-' : '+' }} {{ format_number($record->amount, 2) }}
                                </td>
                                <td class="text-nowrap">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('financial-records.show', $record) }}" class="btn btn-info text-white" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($record->type === 'revenue')
                                            <a href="{{ route('cycles.editSale', [$cycle, $record]) }}" class="btn btn-warning" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('cycles.destroySale', [$cycle, $record]) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف مبيعات الدورة؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('financial-records.edit', $record) }}" class="btn btn-warning" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('financial-records.destroy', $record) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف السجل المالي؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    @endif {{-- end financial-prices check for financial records tab --}}

    <!-- Cycle Statement Tab - يظهر فقط لمن يملك صلاحية الأسعار -->
    @if($user && $user->hasPermission('financial-prices'))
    <div class="tab-pane fade" id="statement" role="tabpanel">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">قائمة الدخل للدورة (كشف حساب)</h5>
            <a href="{{ route('cycles.export_statement', $cycle) }}" class="btn btn-success">
                <i class="fas fa-file-excel me-2"></i>استخراج كإكسيل (CSV)
            </a>
        </div>

        {{-- ملخص الأدوار في الكشف --}}
        @php
            $floorChicksReport = $cycle->floor_chicks ?? [];
            $mortalityByFloorReport = $cycle->mortalityByFloor;
            $floorsReport = $cycle->shed->floors ?? 1;
        @endphp
        @if(count($floorChicksReport) > 0)
        <div class="card mb-3 border-info">
            <div class="card-header bg-info text-white py-2">
                <i class="fas fa-layer-group me-2"></i>ملخص الأدوار
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0 text-center">
                        <thead class="table-light">
                            <tr>
                                @for($f = 1; $f <= $floorsReport; $f++)
                                    <th>الدور {{ $f }}</th>
                                @endfor
                                <th class="table-secondary">الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @for($f = 1; $f <= $floorsReport; $f++)
                                    <td class="text-info fw-bold">{{ format_number($floorChicksReport[$f] ?? 0) }}</td>
                                @endfor
                                <td class="text-info fw-bold table-secondary">{{ format_number($cycle->initial_chicks) }}</td>
                            </tr>
                            <tr>
                                @for($f = 1; $f <= $floorsReport; $f++)
                                    <td class="text-danger small">نافق: {{ $mortalityByFloorReport[$f] ?? 0 }}</td>
                                @endfor
                                <td class="text-danger fw-bold table-secondary">{{ format_number($cycle->mortality_count) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0 text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>التاريخ</th>
                                <th>البيان (الوصف)</th>
                                <th>مدين (مصروف)</th>
                                <th>دائن (إيراد)</th>
                                <th>الرصيد</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $balance = 0;
                                $totalDebit = 0;
                                $totalCredit = 0;
                                // Sort records by date then by id to keep them chronological
                                $sortedRecords = $cycle->financialRecords->sortBy(function($record) {
                                    return $record->record_date->format('Y-m-d') . '-' . $record->id;
                                });
                            @endphp
                            @forelse($sortedRecords as $record)
                                @php
                                    $debit = $record->type === 'expense' ? $record->amount : 0;
                                    $credit = $record->type === 'revenue' ? $record->amount : 0;
                                    $balance += $credit - $debit;
                                    $totalDebit += $debit;
                                    $totalCredit += $credit;
                                @endphp
                                <tr>
                                    <td>{{ $record->record_date->format('Y-m-d') }}</td>
                                    <td class="text-start">
                                        {{ $record->description }}
                                        @if($record->payment_type)
                                            <span class="badge bg-{{ $record->payment_type === 'cash' ? 'info' : 'warning text-dark' }} ms-1">
                                                {{ $record->payment_type === 'cash' ? 'كاش' : 'اجل' }}
                                            </span>
                                        @endif
                                        @if($record->client)
                                            <br><small class="text-primary">على حساب: {{ $record->client->name }}</small>
                                        @endif
                                    </td>
                                    <td class="text-danger">{{ $debit > 0 ? format_number($debit, 2) : '-' }}</td>
                                    <td class="text-success">{{ $credit > 0 ? format_number($credit, 2) : '-' }}</td>
                                    <td class="fw-bold" dir="ltr">
                                        <span class="text-{{ $balance > 0 ? 'success' : ($balance < 0 ? 'danger' : 'dark') }}">
                                            {{ format_number($balance, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">لا توجد حركات مالية مسجلة</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light fw-bold fs-5">
                            <tr>
                                <td colspan="2" class="text-end pe-4">الإجمالي:</td>
                                <td class="text-danger">{{ format_number($totalDebit, 2) }}</td>
                                <td class="text-success">{{ format_number($totalCredit, 2) }}</td>
                                <td dir="ltr">
                                    <span class="text-{{ $balance > 0 ? 'success' : ($balance < 0 ? 'danger' : 'dark') }}">
                                        {{ format_number($balance, 2) }}
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif {{-- end financial-prices check for statement tab --}}

    <!-- Dispensations Log Tab -->
    <div class="tab-pane fade" id="dispensations" role="tabpanel">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>سجل صرف العلف والأدوية</h5>
                @if ($cycle->status === 'active')
                    <a href="{{ route('inventory.dispense.create', $cycle->id) }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus me-1"></i>صرف جديد من مخزن العنبر
                    </a>
                @endif
            </div>
            <div class="card-body p-0">
                @if($cycle->dispensations->isEmpty())
                    <div class="alert alert-info m-3">
                        <i class="fas fa-info-circle me-2"></i>لا توجد عمليات صرف مسجلة لهذه الدورة
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 text-center align-middle">
                            <thead class="table-secondary">
                                <tr>
                                    <th>التاريخ</th>
                                    <th>الدور</th>
                                    <th>الصنف</th>
                                    <th>الكمية</th>
                                    @if($user && $user->hasPermission('financial-prices'))
                                    <th>التكلفة</th>
                                    @endif
                                    <th>ملاحظات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cycle->dispensations as $disp)
                                    <tr>
                                        <td>{{ $disp->dispensation_date->format('Y-m-d') }}</td>
                                        <td><span class="badge bg-secondary">الدور {{ $disp->floor_number }}</span></td>
                                        <td class="fw-bold">{{ $disp->item->name }}</td>
                                        <td>{{ format_number($disp->quantity, 3) }} {{ $disp->item->unit }}</td>
                                        @if($user && $user->hasPermission('financial-prices'))
                                        <td class="text-danger fw-bold">{{ format_number($disp->total_cost, 2) }} ج.م</td>
                                        @endif
                                        <td class="text-start small text-muted">{{ $disp->notes ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Feed Log Tab -->
    <div class="tab-pane fade" id="feed-log" role="tabpanel">
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <h5 class="mb-0"><i class="fas fa-seedling me-2"></i>تقرير استهلاك العلف التفصيلي</h5>
                    <select id="feedFloorFilter" class="form-select form-select-sm w-auto fw-bold text-dark border-dark">
                        <option value="all">كل الأدوار</option>
                        @for($f = 1; $f <= $floors; $f++)
                            <option value="{{ $f }}">الدور {{ $f }}</option>
                        @endfor
                    </select>
                </div>
                <span class="badge bg-dark fs-6">إجمالي: <span id="feedTotalBadge">{{ format_number($cycle->total_feed_consumed, 2) }}</span> شيكارة</span>
            </div>
            <div class="card-body p-0">
                @php
                    $feedDispensations = $cycle->dispensations->filter(fn($d) => $d->item->category === 'feed');
                @endphp
                @if($feedDispensations->isEmpty())
                    <div class="alert alert-warning m-3">
                        <i class="fas fa-info-circle me-2"></i>لا توجد عمليات صرف علف مسجلة لهذه الدورة
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 text-center align-middle">
                            <thead class="table-warning text-dark">
                                <tr>
                                    <th>التاريخ</th>
                                    <th>الدور</th>
                                    <th>نوع العلف</th>
                                    <th>الكمية (شيكارة)</th>
                                    @if($user && $user->hasPermission('financial-prices'))
                                    <th>إجمالي التكلفة</th>
                                    @endif
                                    <th>ملاحظات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($feedDispensations as $disp)
                                    <tr class="feed-row" data-floor="{{ $disp->floor_number }}" data-qty="{{ $disp->quantity }}" data-cost="{{ $disp->total_cost }}">
                                        <td>{{ $disp->dispensation_date->format('Y-m-d') }}</td>
                                        <td><span class="badge bg-secondary">الدور {{ $disp->floor_number }}</span></td>
                                        <td class="fw-bold text-primary">{{ $disp->item->name }}</td>
                                        <td class="text-success fw-bold">{{ format_number($disp->quantity, 2) }}</td>
                                        @if($user && $user->hasPermission('financial-prices'))
                                        <td class="text-danger fw-bold">{{ format_number($disp->total_cost, 2) }} ج.م</td>
                                        @endif
                                        <td class="text-start small text-muted">{{ $disp->notes ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light fw-bold fs-5">
                                <tr>
                                    <td colspan="3" class="text-end pe-4">الإجمالي:</td>
                                    <td class="text-success" id="feedFooterQty">{{ format_number($feedDispensations->sum('quantity'), 2) }}</td>
                                    @if($user && $user->hasPermission('financial-prices'))
                                    <td class="text-danger" id="feedFooterCost">{{ format_number($feedDispensations->sum('total_cost'), 2) }} ج.م</td>
                                    @endif
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('sheds.show', $cycle->shed) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>العودة
    </a>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        const qtyFormatter = new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        });
        const moneyFormatter = new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        });

        $('#feedFloorFilter').on('change', function () {
            const selectedFloor = $(this).val();
            const rows = document.querySelectorAll('.feed-row');
            let totalQty = 0;
            let totalCost = 0;

            rows.forEach(row => {
                const rowFloor = row.getAttribute('data-floor');
                if (selectedFloor === 'all' || rowFloor === selectedFloor) {
                    row.style.display = '';
                    totalQty += parseFloat(row.getAttribute('data-qty')) || 0;
                    totalCost += parseFloat(row.getAttribute('data-cost')) || 0;
                } else {
                    row.style.display = 'none';
                }
            });

            $('#feedFooterQty').text(qtyFormatter.format(totalQty));
            $('#feedFooterCost').text(moneyFormatter.format(totalCost) + ' ج.م');
            $('#feedTotalBadge').text(qtyFormatter.format(totalQty));
        });
    });
</script>
@endsection
