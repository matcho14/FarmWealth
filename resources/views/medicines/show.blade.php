@extends('components.master')

@section('title', 'كارت صنف - ' . $medicine->name)

@section('content')
<div class="page-header mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1><i class="fas fa-file-medical-alt me-2 text-primary"></i> كارت صنف: {{ $medicine->name }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('medicines.index') }}">دليل الأدوية</a></li>
                <li class="breadcrumb-item active">تفاصيل الصنف</li>
            </ol>
        </nav>
    </div>
    <div class="widgetbar">
        <a href="{{ route('medicines.entries.create', $medicine) }}" class="btn btn-success shadow-sm">
            <i class="fas fa-plus"></i> إضافة شحنة جديدة
        </a>
    </div>
</div>

<div class="row">
    <!-- ملخص الرصيد -->
    <div class="col-md-4 mb-4">
        <div class="card bg-primary text-white shadow-sm h-100 border-0">
            <div class="card-body text-center d-flex flex-column justify-content-center">
                <p class="mb-1 opacity-75">إجمالي الرصيد المتاح حالياً</p>
                <h1 class="display-3 fw-bold mb-0 text-white">{{ format_number($medicine->current_stock, 2) }}</h1>
                <p class="fs-5">{{ $medicine->unit }}</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm h-100 border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 text-dark fw-bold"><i class="fas fa-info-circle me-1"></i> معلومات الدواء</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <label class="text-muted d-block small">اسم الصنف</label>
                        <span class="fw-bold fs-5 text-primary">{{ $medicine->name }}</span>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="text-muted d-block small">وحدة التعامل</label>
                        <span class="fw-bold fs-5 text-dark">{{ $medicine->unit }}</span>
                    </div>
                    <div class="col-12 mt-2">
                        <label class="text-muted d-block small">الوصف</label>
                        <p class="mb-0 p-2 bg-light rounded text-dark">{{ $medicine->description ?: 'لا يوجد وصف سجل لهذا الدواء.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- نظام FIFO وتفاصيل الشحنات -->
<div class="card shadow-sm border-0 mb-4 overflow-hidden">
    <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-layer-group me-1"></i> تفاصيل الشحنات المتاحة (نظام FIFO)</h5>
        <span class="badge bg-warning text-dark px-3 py-2">يتم السحب من الأقدم أولاً</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 text-center align-middle">
            <thead class="bg-light">
                <tr>
                    <th>تاريخ الورود</th>
                    <th>الكمية الكلية</th>
                    <th>الكمية المتبقية</th>
                    <th>سعر الوحدة</th>
                    <th>حالة الشحنة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($entries as $entry)
                <tr>
                    <td>{{ $entry->entry_date }}</td>
                    <td>{{ format_number($entry->quantity, 2) }}</td>
                    <td class="fw-bold {{ $entry->remaining_quantity > 0 ? 'text-success' : 'text-danger' }}">
                        {{ format_number($entry->remaining_quantity, 2) }}
                    </td>
                    <td class="text-primary fw-bold">{{ format_number($entry->price, 2) }} ج.م</td>
                    <td>
                        @if($entry->remaining_quantity <= 0)
                            <span class="badge bg-danger">منتهية</span>
                        @elseif($entry->remaining_quantity < $entry->quantity)
                            <span class="badge bg-warning text-dark">جارِ الصرف</span>
                        @else
                            <span class="badge bg-success">جديدة</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-4 text-muted">لا توجد شحنات مسجلة لهذا الدواء.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- سجل الصرف لدورات -->
<div class="card shadow-sm border-0 overflow-hidden">
    <div class="card-header bg-secondary text-white py-3">
        <h5 class="mb-0"><i class="fas fa-history me-1"></i> سجل عمليات الصرف على الدورات</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 text-center align-middle">
            <thead class="bg-light text-dark">
                <tr>
                    <th>تاريخ الصرف</th>
                    <th>اسم العنبر / الدورة</th>
                    <th>الكمية</th>
                    <th>التكلفة المحملة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dispensations as $disp)
                <tr>
                    <td>{{ $disp->dispensation_date }}</td>
                    <td>
                        <div class="d-flex flex-column align-items-center">
                            <span class="text-muted small">{{ $disp->cycle->shed->name ?? 'عنبر غير مسمى' }}</span>
                            <a href="{{ route('cycles.show', $disp->cycle_id) }}" class="text-decoration-none fw-bold text-primary">
                                {{ $disp->cycle->name }}
                            </a>
                        </div>
                    </td>
                    <td><span class="badge bg-light text-dark border">{{ format_number($disp->quantity, 2) }} {{ $medicine->unit }}</span></td>
                    <td class="text-danger fw-bold">{{ format_number($disp->total_cost, 2) }} ج.م</td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-4 text-muted">لم يتم صرف هذا الدواء لأي دورة بعد.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
