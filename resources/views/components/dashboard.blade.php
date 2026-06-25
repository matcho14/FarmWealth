@extends('components.master')

@section('title', 'لوحة التحكم')

@section('content')
<div class="page-header mb-4">
    <h1><i class="fas fa-tachometer-alt"></i> لوحة التحكم</h1>
    <p class="text-muted">ملخص شامل لعمليات المزرعة</p>
</div>

<!-- KPI Cards Row 1 -->
<div class="row mb-4">
    @if(auth()->user()->hasPermission('sheds'))
    <div class="col-md-3 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-home" style="font-size: 2.5rem; color: #3498db;"></i>
                </div>
                <h6 class="card-title text-muted mb-2">عدد العنابر</h6>
                <h2 class="mb-2" style="color: #3498db;">{{ $totalSheds }}</h2>
                <a href="{{ route('sheds.index') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
            </div>
        </div>
    </div>
    @endif

    @if(auth()->user()->hasPermission('cycles'))
    <div class="col-md-3 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-circle-notch" style="font-size: 2.5rem; color: #27ae60;"></i>
                </div>
                <h6 class="card-title text-muted mb-2">الدورات النشطة</h6>
                <h2 class="mb-2" style="color: #27ae60;">{{ $activeCycles }}</h2>
                <a href="{{ route('cycles.index') }}" class="btn btn-sm btn-outline-success">عرض الكل</a>
            </div>
        </div>
    </div>
    @endif

    @if(auth()->user()->hasPermission('annual-report'))
    <div class="col-md-3 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-check-circle" style="font-size: 2.5rem; color: #f39c12;"></i>
                </div>
                <h6 class="card-title text-muted mb-2">الدورات المغلقة</h6>
                <h2 class="mb-2" style="color: #f39c12;">{{ $completedCycles }}</h2>
                <a href="{{ route('annual-report') }}" class="btn btn-sm btn-outline-warning">تقرير سنوي</a>
            </div>
        </div>
    </div>
    @endif

    @if(auth()->user()->hasPermission('cycles'))
    <div class="col-md-3 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-egg" style="font-size: 2.5rem; color: #e67e22;"></i>
                </div>
                <h6 class="card-title text-muted mb-2">إجمالي الكتاكيت</h6>
                <h2 class="mb-2" style="color: #e67e22;">{{ format_number($totalChicks) }}</h2>
                <small class="text-muted">منذ البداية</small>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- KPI Cards Row 2 -->
<div class="row mb-4">
    @if(auth()->user()->hasPermission('cycles'))
    <div class="col-md-3 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-skull" style="font-size: 2.5rem; color: #e74c3c;"></i>
                </div>
                <h6 class="card-title text-muted mb-2">إجمالي النافق</h6>
                <h2 class="mb-2" style="color: #e74c3c;">{{ format_number($totalMortality) }}</h2>
                @php
                    $mortalityRate = $totalChicks ? (($totalMortality / $totalChicks) * 100) : 0;
                @endphp
                <small class="text-muted">معدل: {{ format_number($mortalityRate, 1) }}%</small>
            </div>
        </div>
    </div>
    @endif

    @if(auth()->user()->hasPermission('annual-report'))
    <div class="col-md-3 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-money-bill-wave" style="font-size: 2.5rem; color: #27ae60;"></i>
                </div>
                <h6 class="card-title text-muted mb-2">إجمالي الأرباح</h6>
                <h2 class="mb-2" style="color: #27ae60;">{{ format_number($totalProfit, 2) }}</h2>
                <small class="text-muted">من الدورات المغلقة</small>
            </div>
        </div>
    </div>
    @endif

    <div class="col-md-6 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <h6 class="card-title mb-3"><i class="fas fa-plus-circle me-2"></i>إجراءات سريعة</h6>
                <div class="d-flex flex-wrap gap-2">
                    @if(auth()->user()->hasPermission('sheds'))
                    <a href="{{ route('sheds.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-2"></i>إضافة عنبر
                    </a>
                    @endif
                    @if(auth()->user()->hasPermission('cycles'))
                    <a href="{{ route('cycles.index') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-sync-alt me-2"></i>عرض الدورات
                    </a>
                    @endif
                    @if(auth()->user()->hasPermission('annual-report'))
                    <a href="{{ route('annual-report') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-chart-line me-2"></i>التقرير السنوي
                    </a>
                    @endif
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('users.index') }}" class="btn btn-dark btn-sm">
                        <i class="fas fa-users-cog me-2"></i>إدارة المستخدمين
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Cycles Table -->
@if(auth()->user()->hasPermission('cycles'))
    @if ($recentCycles->count() > 0)
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>آخر الدورات</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 text-center">
                    <thead class="table-light">
                        <tr>
                            <th>رقم الدورة</th>
                            <th>العنبر</th>
                            <th>تاريخ البداية</th>
                            <th>الحالة</th>
                            <th>الكتاكيت الأولي</th>
                            <th>النافق</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentCycles as $cycle)
                            <tr>
                                <td><strong>#{{ $cycle->id }}</strong></td>
                                <td>
                                    @if(auth()->user()->hasPermission('sheds'))
                                    <a href="{{ route('sheds.show', $cycle->shed) }}" class="text-decoration-none">
                                        {{ $cycle->shed->name }}
                                    </a>
                                    @else
                                        {{ $cycle->shed->name }}
                                    @endif
                                </td>
                                <td>{{ $cycle->start_date->format('Y-m-d') }}</td>
                                <td>
                                    <span class="badge bg-{{ $cycle->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ $cycle->status === 'active' ? 'نشطة' : 'مغلقة' }}
                                    </span>
                                </td>
                                <td><span class="badge bg-info">{{ $cycle->initial_chicks }}</span></td>
                                <td><span class="badge bg-warning">{{ $cycle->mortality_count }}</span></td>
                                <td>
                                    <a href="{{ route('cycles.show', $cycle) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> عرض
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-info alert-dismissible fade show mt-4" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            <strong>لا توجد دورات حالياً!</strong>
            @if(auth()->user()->hasPermission('sheds'))
                ابدأ بـ <a href="{{ route('sheds.create') }}" class="alert-link">إنشاء عنبر</a> ثم انقر على العنبر لإنشاء دورة بداخله.
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
@endif
@endsection
