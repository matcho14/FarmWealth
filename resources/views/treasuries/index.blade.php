@extends('components.master')
@section('title','الخزائن')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-vault me-2 text-warning"></i>الخزائن</h3>
    <a href="{{ route('treasuries.create') }}" class="btn btn-success"><i class="fas fa-plus me-1"></i>خزنة جديدة</a>
</div>
@if($treasuries->isEmpty())
    <div class="alert alert-info">لا توجد خزائن مسجلة.</div>
@else
<div class="row g-3">
    @foreach($treasuries as $t)
    <div class="col-md-4">
        <div class="card border-warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="card-title mb-0"><i class="fas fa-vault me-2 text-warning"></i>{{ $t->name }}</h5>
                    <div>
                        <a href="{{ route('treasuries.edit',$t) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('treasuries.destroy',$t) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('حذف الخزنة؟')"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
                <p class="text-muted small mb-2">رصيد افتتاحي: {{ format_number($t->opening_balance,2) }}</p>
                <h3 class="text-{{ $t->balance >= 0 ? 'success' : 'danger' }} mb-0">
                    {{ format_number($t->balance,2) }} <small class="fs-6">ج.م</small>
                </h3>
                <a href="{{ route('treasuries.show',$t) }}" class="btn btn-info btn-sm text-white mt-3 w-100">
                    <i class="fas fa-file-alt me-1"></i>كشف الحساب
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
