@extends('components.master')

@section('title', 'التقرير السنوي')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="fas fa-chart-bar me-2"></i>التقرير السنوي {{ $year }}</h2>
    </div>
    <div class="col-md-4 text-end">
        <form method="GET" action="{{ route('annual-report') }}" class="d-flex gap-2">
            <input type="number" name="year" class="form-control" placeholder="السنة" value="{{ $year }}" min="2020" max="2099">
            <button type="submit" class="btn btn-primary">تحديث</button>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-title text-muted">عدد الدورات</h6>
                <h3 class="text-primary">{{ count($cycles) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-title text-muted">إجمالي المصروفات</h6>
                <h3 class="text-danger">{{ format_number($totalExpenses, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-title text-muted">إجمالي الإيرادات</h6>
                <h3 class="text-success">{{ format_number($totalRevenues, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-title text-muted">صافي الحاصل</h6>
                <h3 class="text-{{ $netProfit > 0 ? 'success' : 'danger' }}">{{ format_number($netProfit, 2) }}</h3>
            </div>
        </div>
    </div>
</div>

@if ($cycles->isEmpty())
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>لا توجد دورات مغلقة في السنة {{ $year }}
    </div>
@else
    <!-- Cycles Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">الدورات المغلقة</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>الدورة</th>
                        <th>العنبر</th>
                        <th>المدة</th>
                        <th>الكتاكيت الأولي</th>
                        <th>المباع</th>
                        <th>المصروفات</th>
                        <th>الإيرادات</th>
                        <th>صافي الحاصل</th>
                        <th>الهامش</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cycles as $cycle)
                        @php
                            $margin = $cycle->total_revenues > 0 ? (($cycle->net_profit / $cycle->total_revenues) * 100) : 0;
                        @endphp
                        <tr>
                            <td><a href="{{ route('cycles.show', $cycle) }}">#{{ $cycle->id }}</a></td>
                            <td><a href="{{ route('sheds.show', $cycle->shed) }}">{{ $cycle->shed->name }}</a></td>
                            <td>{{ $cycle->start_date->format('d/m') }} - {{ $cycle->end_date->format('d/m') }}</td>
                            <td><span class="badge bg-info">{{ $cycle->initial_chicks }}</span></td>
                            <td><span class="badge bg-primary">{{ $cycle->sold_chicks }}</span></td>
                            <td class="text-danger">{{ format_number($cycle->total_expenses, 2) }}</td>
                            <td class="text-success">{{ format_number($cycle->total_revenues, 2) }}</td>
                            <td class="text-{{ $cycle->net_profit > 0 ? 'success' : 'danger' }}">
                                {{ format_number($cycle->net_profit, 2) }}
                            </td>
                            <td class="text-{{ $margin > 0 ? 'success' : 'danger' }}">{{ format_number($margin, 1) }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Print Button -->
    <div class="mt-4">
        <button onclick="window.print()" class="btn btn-secondary">
            <i class="fas fa-print me-2"></i>طباعة التقرير
        </button>
    </div>
@endif
@endsection
