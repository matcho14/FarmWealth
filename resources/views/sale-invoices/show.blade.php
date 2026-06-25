@extends('components.master')
@section('title','عرض فاتورة البيع')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3><i class="fas fa-file-invoice me-2 text-primary"></i>فاتورة بيع رقم: {{ $saleInvoice->invoice_number }}</h3>
        <p class="text-muted mb-0">تاريخ: {{ $saleInvoice->invoice_date->format('Y-m-d') }}</p>
    </div>
    <div class="text-end">
        <span class="badge bg-{{ $saleInvoice->payment_status === 'paid' ? 'success' : ($saleInvoice->payment_status === 'partial' ? 'warning' : 'danger') }} fs-5">
            {{ $saleInvoice->payment_status === 'paid' ? 'مدفوع' : ($saleInvoice->payment_status === 'partial' ? 'مدفوع جزئياً' : 'غير مدفوع') }}
        </span>
    </div>
</div>

<div class="row mb-3 g-3">
    <div class="col-md-4">
        <div class="card text-center border-info">
            <div class="card-body py-2">
                <small class="text-muted">العميل</small>
                <h5 class="text-info mb-0">{{ $saleInvoice->client->name ?? '-' }}</h5>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-success">
            <div class="card-body py-2">
                <small class="text-muted">الإجمالي</small>
                <h5 class="text-success mb-0">{{ format_number($saleInvoice->total_amount,2) }}</h5>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-danger">
            <div class="card-body py-2">
                <small class="text-muted">المتبقي</small>
                <h5 class="text-danger mb-0">{{ format_number($saleInvoice->remaining_amount,2) }}</h5>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header fw-bold">أصناف الفاتورة</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-striped mb-0 text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th><th>اسم الصنف</th><th>الكمية</th><th>سعر الوحدة</th><th>الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($saleInvoice->items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-start">{{ $item->item->name ?? '-' }}</td>
                        <td>{{ format_number($item->quantity, 3) }}</td>
                        <td>{{ format_number($item->unit_price, 2) }}</td>
                        <td>{{ format_number($item->total, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">لا توجد أصناف في هذه الفاتورة</td></tr>
                    @endforelse
                </tbody>
                <tfoot class="table-dark fw-bold">
                    <tr>
                        <td colspan="4" class="text-end">الإجمالي الكلي</td>
                        <td>{{ format_number($saleInvoice->total_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@if($saleInvoice->notes)
<div class="card mt-3">
    <div class="card-header fw-bold">ملاحظات</div>
    <div class="card-body">{{ $saleInvoice->notes }}</div>
</div>
@endif

<div class="mt-3">
    <a href="{{ route('sale-invoices.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>العودة
    </a>
</div>
@endsection