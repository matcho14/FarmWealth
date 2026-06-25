@extends('components.master')
@section('title','تفاصيل مبيعات الدورة')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3><i class="fas fa-shopping-cart me-2 text-primary"></i>تفاصيل مبيعات الدورة #{{ $cycle->id }}</h3>
        <p class="text-muted mb-0">{{ $financialRecord->record_date->format('Y-m-d') }}</p>
    </div>
    <div>
        <a href="{{ route('cycles.editSale', [$cycle, $financialRecord]) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i>تعديل
        </a>
        <form action="{{ route('cycles.destroySale', [$cycle, $financialRecord]) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف مبيعات الدورة؟')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash me-1"></i>حذف
            </button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <small class="text-muted d-block">عدد الكتاكيت المباعة</small>
                <strong>{{ $financialRecord->quantity }}</strong>
            </div>
            <div class="col-md-4">
                <small class="text-muted d-block">إجمالي الوزن</small>
                <strong>{{ format_number($financialRecord->weight, 2) }} كجم</strong>
            </div>
            <div class="col-md-4">
                <small class="text-muted d-block">متوسط الوزن</small>
                <strong>{{ format_number($financialRecord->average_weight, 3) }} كجم</strong>
            </div>
            <div class="col-md-4">
                <small class="text-muted d-block">إجمالي المبلغ</small>
                <strong class="text-success">{{ format_number($financialRecord->amount, 2) }}</strong>
            </div>
            <div class="col-md-4">
                <small class="text-muted d-block">نوع الدفع</small>
                <strong>{{ $financialRecord->payment_type === 'cash' ? 'كاش' : 'إجل' }}</strong>
            </div>
            <div class="col-md-4">
                <small class="text-muted d-block">الخزنة</small>
                <strong>{{ $financialRecord->treasury?->name ?? '-' }}</strong>
            </div>
            <div class="col-md-6">
                <small class="text-muted d-block">العميل</small>
                <strong>{{ $financialRecord->client?->name ?? '-' }}</strong>
            </div>
            <div class="col-md-6">
                <small class="text-muted d-block">الوصف</small>
                <strong>{{ $financialRecord->description }}</strong>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('cycles.show', $cycle) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>العودة للدورة
    </a>
</div>
@endsection
