@extends('components.master')
@section('title','تفاصيل السجل المالي')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3><i class="fas fa-coins me-2 text-primary"></i>تفاصيل السجل المالي</h3>
        <p class="text-muted mb-0">الدورة #{{ $financialRecord->cycle->id }} | {{ $financialRecord->record_date->format('Y-m-d') }}</p>
    </div>
    <div>
        <a href="{{ route('financial-records.edit', $financialRecord) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i>تعديل
        </a>
        <form action="{{ route('financial-records.destroy', $financialRecord) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف السجل المالي؟')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash me-1"></i>حذف
            </button>
        </form>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <small class="text-muted d-block">النوع</small>
                <strong class="text-{{ $financialRecord->type === 'expense' ? 'danger' : 'success' }}">
                    {{ $financialRecord->type === 'expense' ? 'مصروف' : 'إيراد' }}
                </strong>
            </div>
            <div class="col-md-3">
                <small class="text-muted d-block">المبلغ</small>
                <strong class="text-{{ $financialRecord->type === 'expense' ? 'danger' : 'success' }}">{{ format_number($financialRecord->amount, 2) }}</strong>
            </div>
            <div class="col-md-3">
                <small class="text-muted d-block">الخزنة</small>
                <strong>{{ $financialRecord->treasury?->name ?? '-' }}</strong>
            </div>
            <div class="col-md-3">
                <small class="text-muted d-block">الحساب المحاسبي</small>
                <strong>{{ $financialRecord->chartOfAccount?->full_code ?? '-' }} {{ $financialRecord->chartOfAccount?->name ?? '' }}</strong>
            </div>
            <div class="col-md-3">
                <small class="text-muted d-block">العنبر</small>
                <strong>{{ $financialRecord->shed?->name ?? ($financialRecord->cycle?->shed?->name ?? '-') }}</strong>
            </div>
            <div class="col-md-3">
                <small class="text-muted d-block">الدورة</small>
                <strong>#{{ $financialRecord->cycle?->id ?? '-' }}</strong>
            </div>
            <div class="col-md-3">
                <small class="text-muted d-block">نوع الدفع</small>
                <strong>{{ $financialRecord->payment_type === 'cash' ? 'كاش' : ($financialRecord->payment_type === 'credit' ? 'إجل' : '-') }}</strong>
            </div>
            <div class="col-md-3">
                <small class="text-muted d-block">الكمية / الوزن</small>
                <strong>{{ $financialRecord->quantity ?? '-' }} / {{ format_number($financialRecord->weight ?? 0, 2) }}</strong>
            </div>
            <div class="col-12">
                <small class="text-muted d-block">الوصف</small>
                <p class="mb-0">{{ $financialRecord->description }}</p>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('cycles.show', $financialRecord->cycle) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>العودة للدورة
    </a>
</div>
@endsection
