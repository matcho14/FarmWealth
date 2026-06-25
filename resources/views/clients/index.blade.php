@extends('components.master')
@section('title','العملاء')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-users me-2 text-primary"></i>العملاء</h3>
    <a href="{{ route('clients.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-1"></i>عميل جديد
    </a>
</div>

@if($clients->isEmpty())
    <div class="alert alert-info">لا يوجد عملاء مسجلون بعد.</div>
@else
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th><th>اسم العميل</th><th>الهاتف</th>
                        <th>رصيد افتتاحي</th><th>الرصيد الحالي</th><th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $c)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-bold">{{ $c->name }}</td>
                        <td>{{ $c->phone ?? '-' }}</td>
                        <td>{{ format_number($c->opening_balance,2) }}</td>
                        <td>
                            <span class="badge bg-{{ $c->balance > 0 ? 'success' : 'danger' }} fs-6">
                                {{ format_number($c->balance,2) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('clients.show',$c) }}" class="btn btn-sm btn-info text-white">
                                <i class="fas fa-file-alt"></i> كشف حساب
                            </a>
                            @if($c->hasChartAccount())
                                <a href="{{ route('clients.edit',$c) }}" class="btn btn-sm btn-warning" title="تعديل بيانات العميل">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @else
                                <a href="{{ route('chart-of-accounts.create') }}?linkable_type={{ urlencode(\App\Models\Client::class) }}&linkable_id={{ $c->id }}&name={{ urlencode($c->name) }}&suggested_code=110" class="btn btn-sm btn-primary" title="ربط بشجرة الحسابات">
                                    <i class="fa-solid fa-link"></i> ربط بالشجرة
                                </a>
                            @endif
                            <form action="{{ route('clients.destroy',$c) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('حذف العميل؟')">
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