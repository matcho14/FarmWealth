@extends('components.master')

@section('title', 'العنابر')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2><i class="fas fa-house-farm me-2"></i>العنابر</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('sheds.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>إضافة عنبر جديد
        </a>
    </div>
</div>

@if ($sheds->isEmpty())
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>لا توجد عنابر حالياً. يمكنك <a href="{{ route('sheds.create') }}">إنشاء عنبر جديد</a>
    </div>
@else
    <div class="row">
        @foreach ($sheds as $shed)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ $shed->name }}</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">{{ $shed->description ?? 'بدون وصف' }}</p>
                        <div class="mb-3">
                            <span class="badge bg-{{ $shed->status === 'active' ? 'success' : 'danger' }}">
                                {{ $shed->status === 'active' ? 'نشط' : 'غير نشط' }}
                            </span>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-sync-alt me-1"></i>
                                دورات نشطة: <strong>{{ count($shed->cycles) }}</strong>
                            </small>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <a href="{{ route('sheds.show', $shed) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye me-1"></i>التفاصيل
                        </a>
                        <a href="{{ route('sheds.edit', $shed) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit me-1"></i>تعديل
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
