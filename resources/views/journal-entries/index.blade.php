@extends('components.master')
@section('title','القيود اليومية')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-exchange-alt me-2 text-primary"></i>القيود اليومية</h3>
    <a href="{{ route('journal-entries.create') }}" class="btn btn-success"><i class="fas fa-plus me-1"></i>قيد جديد</a>
</div>
@if($entries->isEmpty())
    <div class="alert alert-info">لا توجد قيود مسجلة.</div>
@else
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 text-center">
                <thead class="table-dark">
                    <tr><th>رقم القيد</th><th>التاريخ</th><th>البيان</th><th>إجمالي مدين</th><th>إجمالي دائن</th><th>الإجراءات</th></tr>
                </thead>
                <tbody>
                    @foreach($entries as $e)
                    <tr>
                        <td class="fw-bold">{{ $e->entry_number }}</td>
                        <td>{{ $e->entry_date->format('Y-m-d') }}</td>
                        <td class="text-start">{{ $e->description }}</td>
                        <td class="text-success">{{ format_number($e->lines->sum('debit'),2) }}</td>
                        <td class="text-danger">{{ format_number($e->lines->sum('credit'),2) }}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('journal-entries.show',$e) }}" class="btn btn-info text-white" title="عرض"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('journal-entries.edit',$e) }}" class="btn btn-warning" title="تعديل"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('journal-entries.destroy',$e) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا القيد؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" title="حذف"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $entries->links() }}</div>
@endif
@endsection
