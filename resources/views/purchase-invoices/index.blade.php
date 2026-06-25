@extends('components.master')
@section('title','فواتير الشراء')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-file-invoice me-2 text-primary"></i>فواتير الشراء</h3>
    <a href="{{ route('purchase-invoices.create') }}" class="btn btn-success"><i class="fas fa-plus me-1"></i>فاتورة جديدة</a>
</div>
@if($invoices->isEmpty())
    <div class="alert alert-info">لا توجد فواتير مسجلة.</div>
@else
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 text-center">
                <thead class="table-dark">
                    <tr><th>رقم الفاتورة</th><th>المورد</th><th>التاريخ</th><th>الإجمالي</th><th>المدفوع</th><th>المتبقي</th><th>الحالة</th><th>الإجراءات</th></tr>
                </thead>
                <tbody>
                    @foreach($invoices as $inv)
                    <tr>
                        <td class="fw-bold">{{ $inv->invoice_number }}</td>
                        <td>{{ $inv->supplier->name }}</td>
                        <td>{{ $inv->invoice_date->format('Y-m-d') }}</td>
                        <td>{{ format_number($inv->total_amount,2) }}</td>
                        <td class="text-success">{{ format_number($inv->paid_amount,2) }}</td>
                        <td class="text-danger">{{ format_number($inv->remainig_amount,2) }}</td>
                        <td>
                            @php $colors=['unpaid'=>'danger','partial'=>'warning','paid'=>'success']; $labels=['unpaid'=>'غير مدفوعة','partial'=>'جزئي','paid'=>'مدفوعة']; @endphp
                            <span class="badge bg-{{ $colors[$inv->payment_status] }}">{{ $labels[$inv->payment_status] }}</span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('purchase-invoices.show',$inv) }}" class="btn btn-info text-white" title="عرض"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('purchase-invoices.edit',$inv) }}" class="btn btn-warning" title="تعديل"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('purchase-invoices.destroy',$inv) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف فاتورة الشراء؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" title="حذف"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $invoices->links() }}</div>
@endif
@endsection
