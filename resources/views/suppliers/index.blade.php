@extends('components.master')
@section('title','الموردين')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-truck me-2 text-primary"></i>الموردين</h3>
    <a href="{{ route('suppliers.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-1"></i>مورد جديد
    </a>
</div>

@if($suppliers->isEmpty())
    <div class="alert alert-info">لا يوجد موردون مسجلون بعد.</div>
@else
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th><th>اسم المورد</th><th>الهاتف</th>
                        <th>رصيد افتتاحي</th><th>الرصيد الحالي</th><th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suppliers as $s)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-bold">{{ $s->name }}</td>
                        <td>{{ $s->phone ?? '-' }}</td>
                        <td>{{ format_number($s->opening_balance,2) }}</td>
                        <td>
                            <span class="badge bg-{{ $s->balance > 0 ? 'danger' : 'success' }} fs-6">
                                {{ format_number($s->balance,2) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('suppliers.show',$s) }}" class="btn btn-sm btn-info text-white">
                                <i class="fas fa-file-alt"></i> كشف حساب
                            </a>
                            @if($s->hasChartAccount())
                                <a href="{{ route('suppliers.edit',$s) }}" class="btn btn-sm btn-warning" title="تعديل بيانات المورد">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @else
                                <a href="{{ route('chart-of-accounts.create') }}?linkable_type={{ urlencode(\App\Models\Supplier::class) }}&linkable_id={{ $s->id }}&name={{ urlencode($s->name) }}&suggested_code=220" class="btn btn-sm btn-primary" title="ربط بشجرة الحسابات">
                                    <i class="fa-solid fa-link"></i> ربط بالشجرة
                                </a>
                            @endif
                            <form action="{{ route('suppliers.destroy',$s) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('حذف المورد؟')">
                                    <i class="fas fa-trash"></i>
                                </button>
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
