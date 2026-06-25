@extends('components.master')

@section('title', 'تفاصيل العنبر: ' . $shed->name)

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="fas fa-house-farm me-2"></i>{{ $shed->name }}</h2>
        <p class="text-muted">{{ $shed->description ?? 'بدون وصف' }}</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('sheds.edit', $shed) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>تعديل
        </a>
        <form action="{{ route('sheds.destroy', $shed) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد؟')">
                <i class="fas fa-trash me-2"></i>حذف
            </button>
        </form>
    </div>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab" style="color: black !important; font-weight: bold;">
            الدورات النشطة ({{ count($activeCycles) }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" style="color: black !important; font-weight: bold;">
            الدورات المغلقة ({{ count($completedCycles) }})
        </button>
    </li>
</ul>

<div class="tab-content">
    <!-- Active Cycles Tab -->
    <div class="tab-pane fade show active" id="active" role="tabpanel">
        <div class="mb-3">
            <a href="{{ route('cycles.create', $shed) }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>إنشاء دورة جديدة
            </a>
        </div>

        @if ($activeCycles->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>لا توجد دورات نشطة حالياً
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>تاريخ البداية</th>
                            <th>الكتاكيت الأولي</th>
                            <th>النافق</th>
                            <th>المتبقي</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($activeCycles as $cycle)
                            <tr>
                                <td>{{ $cycle->start_date->format('Y-m-d') }}</td>
                                <td><span class="badge bg-info">{{ $cycle->initial_chicks }}</span></td>
                                <td><span class="badge bg-warning">{{ $cycle->mortality_count }}</span></td>
                                <td><span class="badge bg-success">{{ $cycle->expected_remaining }}</span></td>
                                <td>
                                    <a href="{{ route('cycles.show', $cycle) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>عرض
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Completed Cycles Tab -->
    <div class="tab-pane fade" id="completed" role="tabpanel">
        @if ($completedCycles->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>لا توجد دورات مغلقة حالياً
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>من</th>
                            <th>إلى</th>
                            <th>الكتاكيت الأولي</th>
                            <th>النافق</th>
                            <th>المباع</th>
                            @if(auth()->user() && auth()->user()->hasPermission('financial-prices'))
                            <th>صافي الحاصل</th>
                            @endif
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($completedCycles as $cycle)
                            <tr>
                                <td>{{ $cycle->start_date->format('Y-m-d') }}</td>
                                <td>{{ $cycle->end_date->format('Y-m-d') }}</td>
                                <td><span class="badge bg-info">{{ $cycle->initial_chicks }}</span></td>
                                <td><span class="badge bg-warning">{{ $cycle->mortality_count }}</span></td>
                                <td><span class="badge bg-primary">{{ $cycle->sold_chicks }}</span></td>
                                @if(auth()->user() && auth()->user()->hasPermission('financial-prices'))
                                <td>
                                    <span class="badge bg-{{ $cycle->net_profit > 0 ? 'success' : 'danger' }}">
                                        {{ format_number($cycle->net_profit, 2) }}
                                    </span>
                                </td>
                                @endif
                                <td>
                                    <a href="{{ route('cycles.show', $cycle) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>عرض
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
