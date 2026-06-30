@extends('components.master')
@section('title','فواتير البيع')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-file-invoice me-2 text-primary"></i>فواتير البيع</h3>
    <a href="{{ route('sale-invoices.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-1"></i>فاتورة بيع جديدة
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('sale-invoices.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">الدورة</label>
                <select name="cycle_id" class="form-select select2">
                    <option value="">-- الكل --</option>
                    @foreach($cycles as $cycle)
                        <option value="{{ $cycle->id }}" {{ request('cycle_id') == $cycle->id ? 'selected' : '' }}>{{ $cycle->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">العنبر</label>
                <select name="shed_id" class="form-select select2">
                    <option value="">-- الكل --</option>
                    @foreach($sheds as $shed)
                        <option value="{{ $shed->id }}" {{ request('shed_id') == $shed->id ? 'selected' : '' }}>{{ $shed->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i>بحث</button>
            </div>
        </form>
    </div>
</div>

@if($invoices->isEmpty())
    <div class="alert alert-info">لا توجد فواتير بيع مطابقة.</div>
@else
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th><th>رقم الفاتورة</th><th>العميل</th><th>الدورة</th><th>العنبر</th><th>التاريخ</th>
                        <th>الإجمالي</th><th>المدفوع</th><th>المتبقي</th><th>الحالة</th><th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-bold">{{ $invoice->invoice_number }}</td>
                        <td>{{ $invoice->client->name ?? '-' }}</td>
                        <td>{{ $invoice->cycle->name ?? '-' }}</td>
                        <td>{{ $invoice->shed->name ?? '-' }}</td>
                        <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                        <td>{{ format_number($invoice->total_amount, 2) }}</td>
                        <td>{{ format_number($invoice->paid_amount, 2) }}</td>
                        <td>{{ format_number($invoice->remaining_amount, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $invoice->payment_status === 'paid' ? 'success' : ($invoice->payment_status === 'partial' ? 'warning' : 'danger') }}">
                                {{ $invoice->payment_status === 'paid' ? 'مدفوع' : ($invoice->payment_status === 'partial' ? 'مدفوع جزئياً' : 'غير مدفوع') }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('sale-invoices.show', $invoice) }}" class="btn btn-info text-white" title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('sale-invoices.edit', $invoice) }}" class="btn btn-warning" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('sale-invoices.destroy', $invoice) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف فاتورة البيع؟')">
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
            </table>
        </div>
        <div class="mt-3 px-3">
            {{ $invoices->links() }}
        </div>
    </div>
</div>
@endif
@endsection