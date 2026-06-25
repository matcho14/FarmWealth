@extends('components.master')
@section('title','الأصناف')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-boxes me-2 text-primary"></i>الأصناف / المخزون</h3>
    <a href="{{ route('items.create') }}" class="btn btn-success"><i class="fas fa-plus me-1"></i>صنف جديد</a>
</div>
@if($items->isEmpty())
    <div class="alert alert-info">لا توجد أصناف مسجلة.</div>
@else
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 text-center">
                <thead class="table-dark">
                    <tr><th>#</th><th>اسم الصنف</th><th>التصنيف</th><th>الوحدة</th><th>الكمية في المخزن</th><th>آخر سعر شراء</th><th>المورد الافتراضي</th><th>الإجراءات</th></tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-bold">{{ $item->name }}</td>
                        <td>
                            @if($item->category == 'feed') <span class="badge bg-warning text-dark">علف</span>
                            @elseif($item->category == 'medicine') <span class="badge bg-info text-dark">أدوية</span>
                            @else <span class="badge bg-secondary">عام</span> @endif
                        </td>
                        <td>{{ $item->unit }}</td>
                        <td>
                            <span class="badge bg-{{ $item->quantity_in_stock > 0 ? 'success' : 'danger' }} fs-6">
                                {{ format_number($item->quantity_in_stock,3) }}
                            </span>
                        </td>
                        <td>{{ format_number($item->last_purchase_price,2) }}</td>
                        <td>{{ $item->supplier?->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('items.show',$item) }}" class="btn btn-sm btn-info" title="كارت الصنف"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('items.edit',$item) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('items.destroy',$item) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('حذف الصنف؟')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
