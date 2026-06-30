@extends('components.master')
@section('title', 'مخازن العنابر')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-warehouse me-2 text-primary"></i>مخازن العنابر الفرعية</h3>
    <div>
        <a href="{{ route('items.index') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-boxes me-1"></i>المخزن الرئيسي
        </a>
        <a href="{{ route('inventory.export') }}" class="btn btn-success me-2">
            <i class="fas fa-file-excel me-1"></i>تصدير جرد العنابر
        </a>
        <a href="{{ route('inventory.transfer.create') }}" class="btn btn-primary">
            <i class="fas fa-exchange-alt me-1"></i>تحويل لعنبر
        </a>
    </div>
</div>

<div class="row g-4">
    @forelse($sheds as $shed)
    <div class="col-md-6">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-primary fw-bold">
                    <i class="fas fa-home me-2"></i>{{ $shed->name }}
                </h5>
                <span class="badge bg-light text-dark border">{{ $shed->floors }} أدوار</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-start ps-4">الصنف</th>
                                <th>الكمية المتاحة</th>
                                <th>متوسط التكلفة</th>
                                <th>الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shed->inventory as $inv)
                            <tr>
                                <td class="text-start ps-4 fw-bold">{{ $inv->item->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $inv->quantity > 0 ? 'success' : 'danger' }} fs-6">
                                        {{ format_number($inv->quantity, 3) }} {{ $inv->item->unit }}
                                    </span>
                                </td>
                                <td>{{ format_number($inv->avg_unit_cost, 2) }}</td>
                                <td class="fw-bold">{{ format_number($inv->quantity * $inv->avg_unit_cost, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-4 text-muted">لا توجد أصناف في مخزن هذا العنبر حالياً</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info">لا توجد عنابر مسجلة لعرض مخازنها.</div>
    </div>
    @endforelse
</div>
@endsection
