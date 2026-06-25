@extends('components.master')
@section('title','تفاصيل فاتورة الشراء')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3><i class="fas fa-file-invoice me-2 text-primary"></i>فاتورة شراء: {{ $purchaseInvoice->invoice_number }}</h3>
        <p class="text-muted mb-0">{{ $purchaseInvoice->invoice_date->format('Y-m-d') }} | المورد: <strong>{{ $purchaseInvoice->supplier->name }}</strong></p>
    </div>
    @php $colors=['unpaid'=>'danger','partial'=>'warning','paid'=>'success']; $labels=['unpaid'=>'غير مدفوعة','partial'=>'مدفوعة جزئياً','paid'=>'مدفوعة']; @endphp
    <span class="badge bg-{{ $colors[$purchaseInvoice->payment_status] }} fs-5">{{ $labels[$purchaseInvoice->payment_status] }}</span>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-center border-primary py-2">
            <small class="text-muted">إجمالي الفاتورة</small>
            <h4 class="text-primary mb-0">{{ format_number($purchaseInvoice->total_amount,2) }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-success py-2">
            <small class="text-muted">المدفوع</small>
            <h4 class="text-success mb-0">{{ format_number($purchaseInvoice->paid_amount,2) }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-danger py-2">
            <small class="text-muted">المتبقي</small>
            <h4 class="text-danger mb-0">{{ format_number($purchaseInvoice->remainig_amount,2) }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center border-warning py-2">
            <small class="text-muted">الخزنة المستخدمة</small>
            <h5 class="mb-0">{{ $purchaseInvoice->treasury?->name ?? 'آجل' }}</h5>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header fw-bold"><i class="fas fa-boxes me-2"></i>تفاصيل الأصناف</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0 text-center">
                <thead class="table-secondary">
                    <tr><th>#</th><th>الصنف</th><th>الوحدة</th><th>الكمية</th><th>سعر الوحدة</th><th>الإجمالي</th></tr>
                </thead>
                <tbody>
                    @foreach($purchaseInvoice->items as $i => $row)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td class="fw-bold">{{ $row->item->name }}</td>
                        <td>{{ $row->item->unit }}</td>
                        <td>{{ format_number($row->quantity,3) }}</td>
                        <td>{{ format_number($row->unit_price,2) }}</td>
                        <td class="fw-bold text-primary">{{ format_number($row->total,2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="5" class="text-end">الإجمالي</td>
                        <td class="text-primary fs-5">{{ format_number($purchaseInvoice->total_amount,2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@if($purchaseInvoice->notes)
<div class="alert alert-light border"><strong>ملاحظات:</strong> {{ $purchaseInvoice->notes }}</div>
@endif

<div class="mt-3">
    <a href="{{ route('purchase-invoices.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>العودة</a>
    <a href="{{ route('suppliers.show', $purchaseInvoice->supplier) }}" class="btn btn-info text-white ms-2">
        <i class="fas fa-user me-1"></i>كشف حساب المورد
    </a>
</div>
@endsection
