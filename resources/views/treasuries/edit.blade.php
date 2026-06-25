@extends('components.master')
@section('title','تعديل خزنة')
@section('content')
<div class="row justify-content-center"><div class="col-md-5">
<div class="card">
    <div class="card-header"><h5 class="mb-0">تعديل خزنة: {{ $treasury->name }}</h5></div>
    <div class="card-body">
        <form action="{{ route('treasuries.update',$treasury) }}" method="POST">
            @csrf @method('PATCH')
            <div class="mb-3">
                <label class="form-label">اسم الخزنة <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ $treasury->name }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">الرصيد الافتتاحي</label>
                <div class="input-group">
                    <input type="number" name="opening_balance" class="form-control" value="{{ $treasury->opening_balance }}" min="0" step="0.01">
                    <span class="input-group-text">ج.م</span>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">ملاحظات</label>
                <textarea name="notes" class="form-control" rows="2">{{ $treasury->notes }}</textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning flex-grow-1"><i class="fas fa-save me-1"></i>تحديث</button>
                <a href="{{ route('treasuries.index') }}" class="btn btn-secondary flex-grow-1">إلغاء</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
