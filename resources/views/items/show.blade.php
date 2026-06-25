@extends('components.master')
@section('title', 'كارت صنف: ' . $item->name)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-id-card me-2 text-primary"></i>كارت صنف: {{ $item->name }}</h3>
    <a href="{{ route('items.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-right me-1"></i>رجوع للأصناف</a>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white text-center p-3">
            <h6>الرصيد الحالي (المخزن الرئيسي)</h6>
            <h4>{{ format_number($item->quantity_in_stock, 3) }} {{ $item->unit }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white text-center p-3">
            <h6>آخر سعر شراء</h6>
            <h4>{{ format_number($item->last_purchase_price, 2) }} ج.م</h4>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-info text-white p-3">
            <h6>مورد الصنف الافتراضي:</h6>
            <h4 class="mb-0">{{ $item->supplier->name ?? 'غير محدد' }}</h4>
        </div>
    </div>
</div>

<ul class="nav nav-tabs mb-4" id="itemCardTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-bold" id="main-tab" data-bs-toggle="tab" data-bs-target="#main-stock" type="button" role="tab">حركات المخزن الرئيسي</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold" id="shed-tab" data-bs-toggle="tab" data-bs-target="#shed-stock" type="button" role="tab">حركات العنابر</button>
    </li>
</ul>

<div class="tab-content" id="itemCardTabsContent">
    <!-- حركات المخزن الرئيسي -->
    <div class="tab-pane fade show active" id="main-stock" role="tabpanel">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>التاريخ</th>
                                <th>نوع الحركة</th>
                                <th>المرجع</th>
                                <th>وارد (+)</th>
                                <th>منصرف (-)</th>
                                <th>الرصيد التراكمي</th>
                                <th>ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $runningBalance = 0; @endphp
                            @forelse($main_movements as $mov)
                                @php 
                                    $runningBalance += $mov['in'] - $mov['out'];
                                @endphp
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($mov['date'])->format('Y-m-d') }}</td>
                                    <td><span class="badge {{ $mov['in'] > 0 ? 'bg-success' : 'bg-warning text-dark' }}">{{ $mov['type'] }}</span></td>
                                    <td>{{ $mov['reference'] }}</td>
                                    <td class="text-success fw-bold">{{ $mov['in'] > 0 ? format_number($mov['in'], 3) : '-' }}</td>
                                    <td class="text-danger fw-bold">{{ $mov['out'] > 0 ? format_number($mov['out'], 3) : '-' }}</td>
                                    <td class="fw-bold bg-light">{{ format_number($runningBalance, 3) }}</td>
                                    <td>{{ $mov['notes'] }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="7">لا توجد حركات مسجلة.</td></tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-secondary">
                            <tr>
                                <td colspan="3" class="text-start fw-bold">الرصيد النهائي:</td>
                                <td class="text-success fw-bold">{{ format_number($main_movements->sum('in'), 3) }}</td>
                                <td class="text-danger fw-bold">{{ format_number($main_movements->sum('out'), 3) }}</td>
                                <td class="fw-bold fs-5">{{ format_number($runningBalance, 3) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- حركات العنابر -->
    <div class="tab-pane fade" id="shed-stock" role="tabpanel">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>التاريخ</th>
                                <th>العنبر</th>
                                <th>نوع الحركة</th>
                                <th>المرجع</th>
                                <th>وارد (+)</th>
                                <th>منصرف (-)</th>
                                <th>ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shed_movements as $mov)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($mov['date'])->format('Y-m-d') }}</td>
                                    <td class="fw-bold">{{ $mov['shed'] }}</td>
                                    <td><span class="badge {{ $mov['in'] > 0 ? 'bg-primary' : 'bg-danger' }}">{{ $mov['type'] }}</span></td>
                                    <td>{{ $mov['reference'] }}</td>
                                    <td class="text-success fw-bold">{{ $mov['in'] > 0 ? format_number($mov['in'], 3) : '-' }}</td>
                                    <td class="text-danger fw-bold">{{ $mov['out'] > 0 ? format_number($mov['out'], 3) : '-' }}</td>
                                    <td>{{ $mov['notes'] }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="7">لا توجد حركات مسجلة في العنابر.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
