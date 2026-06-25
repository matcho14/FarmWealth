@extends('components.master')

@section('title', 'الدورات')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="fas fa-sync-alt me-2"></i>قائمة الدورات</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('sheds.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>العودة للعنابر
        </a>
    </div>
</div>

@if ($cycles->isEmpty())
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>لا توجد دورات حالياً
    </div>
@else
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>رقم الدورة</th>
                    <th>العنبر</th>
                    <th>الحالة</th>
                    <th>البداية</th>
                    <th>النهاية</th>
                    <th>الكتاكيت الأولي</th>
                    <th>النافق</th>
                    <th>المتبقي</th>
                    <th>استهلاك العلف</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cycles as $cycle)
                    <tr>
                        <td><strong>#{{ $cycle->id }}</strong></td>
                        <td><a href="{{ route('sheds.show', $cycle->shed) }}">{{ $cycle->shed->name }}</a></td>
                        <td>
                            <span class="badge bg-{{ $cycle->status === 'active' ? 'success' : 'secondary' }}">
                                {{ $cycle->status === 'active' ? 'نشطة' : 'مغلقة' }}
                            </span>
                        </td>
                        <td>{{ $cycle->start_date->format('Y-m-d') }}</td>
                        <td>{{ $cycle->end_date ? $cycle->end_date->format('Y-m-d') : '-' }}</td>
                        <td><span class="badge bg-info">{{ $cycle->initial_chicks }}</span></td>
                        <td><span class="badge bg-warning">{{ $cycle->mortality_count }}</span></td>
                        <td><span class="badge bg-success">{{ $cycle->expected_remaining }}</span></td>
                        <td><span class="badge bg-dark text-warning">{{ format_number($cycle->total_feed_consumed, 2) }}</span></td>
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

    {{ $cycles->links() }}
@endif
@endsection
