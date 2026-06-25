@extends('components.master')
@section('title','تفاصيل سجل النافق')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3><i class="fas fa-skull-crossbones me-2 text-warning"></i>تفاصيل سجل النافق</h3>
        <p class="text-muted mb-0">الدورة #{{ $cycle->id }} - {{ $cycle->shed->name }}</p>
    </div>
    <div>
        <a href="{{ route('cycles.editMortalityRecord', [$cycle, $mortalityRecord]) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i>تعديل
        </a>
        <form action="{{ route('cycles.destroyMortalityRecord', [$cycle, $mortalityRecord]) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف سجل النافق؟')">
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
                <small class="text-muted d-block">التاريخ</small>
                <strong>{{ $mortalityRecord->record_date->format('Y-m-d') }}</strong>
            </div>
            <div class="col-md-4">
                <small class="text-muted d-block">الدور</small>
                <strong>الدور {{ $mortalityRecord->floor_number }}</strong>
            </div>
            <div class="col-md-4">
                <small class="text-muted d-block">عدد النافق</small>
                <strong class="text-danger">{{ $mortalityRecord->count }}</strong>
            </div>
            <div class="col-12">
                <small class="text-muted d-block">ملاحظات</small>
                <p class="mb-0">{{ $mortalityRecord->notes ?? '-' }}</p>
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
