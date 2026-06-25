@extends('components.master')

@section('title', 'المخزن - كارت الصنف')

@section('content')
<div class="page-header mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1><i class="fas fa-pills me-2"></i> دليل الأدوية والمخزن</h1>
        <p>متابعة أرصدة الأدوية والكميات المتاحة بنظام الوارد أولاً يصرف أولاً.</p>
    </div>
    <div class="widgetbar">
        <a href="{{ route('medicines.create') }}" class="btn btn-primary shadow-sm me-2">
            <i class="fas fa-plus-circle"></i> إضافة صنف دواء جديد
        </a>
        <a href="{{ route('medicines.dispense.create') }}" class="btn btn-success shadow-sm">
            <i class="fas fa-share-square"></i> صرف دواء لدورة
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <form action="{{ route('medicines.index') }}" method="GET" class="d-flex gap-2">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="ابحث باسم الدواء..." value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-primary px-4">بحث</button>
            @if(request('search'))
                <a href="{{ route('medicines.index') }}" class="btn btn-outline-secondary">إعادة تعيين</a>
            @endif
        </form>
    </div>
</div>

<div class="row">
    @foreach($medicines as $medicine)
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm border-0 transition-hover">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-box-open me-1"></i> {{ $medicine->name }}
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3 p-2 bg-light rounded">
                    <span class="text-muted">الرصيد الحالي:</span>
                    <span class="fw-bold {{ $medicine->total_stock > 0 ? 'text-success' : 'text-danger' }}">
                        {{ format_number($medicine->total_stock, 2) }} {{ $medicine->unit }}
                    </span>
                </div>
                <p class="text-muted small mb-4">{{ Str::limit($medicine->description, 60) ?: 'لا يوجد وصف متاح لهذا الدواء.' }}</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('medicines.show', $medicine) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-list-ul"></i> كارت الصنف (التفاصيل)
                    </a>
                    <a href="{{ route('medicines.entries.create', $medicine) }}" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-plus"></i> إضافة شحنة جديدة
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    @if($medicines->isEmpty())
    <div class="col-12 text-center py-5">
        <div class="display-1 text-muted mb-4"><i class="fas fa-search"></i></div>
        @if(request('search'))
            <h4>لا توجد نتائج للبحث عن "{{ request('search') }}"</h4>
            <p class="text-muted">جرب البحث بكلمة مختلفة أو كلمة أقل دقة.</p>
            <a href="{{ route('medicines.index') }}" class="btn btn-outline-primary mt-3">عرض كافة الأدوية</a>
        @else
            <div class="display-1 text-muted mb-4"><i class="fas fa-capsules"></i></div>
            <h4>لا يوجد أدوية مسجلة بعد</h4>
            <p class="text-muted">ابدأ بإضافة أول صنف دواء لنظام المخزن لديك.</p>
            <a href="{{ route('medicines.create') }}" class="btn btn-primary mt-3">إضافة دواء جديد</a>
        @endif
    </div>
    @endif
</div>

<style>
    .transition-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .transition-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
</style>
@endsection
