@extends('components.master')
@section('title','خزنة جديدة')
@section('content')
<div class="row justify-content-center"><div class="col-md-5">
<div class="card">
    <div class="card-header"><h5 class="mb-0"><i class="fas fa-vault me-2 text-warning"></i>إضافة خزنة جديدة</h5></div>
    <div class="card-body">
        <form action="{{ route('treasuries.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">اسم الخزنة <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" placeholder="مثال: الخزنة الرئيسية" required>
                @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">الرصيد الافتتاحي</label>
                <div class="input-group">
                    <input type="number" name="opening_balance" class="form-control"
                        value="{{ old('opening_balance',0) }}" min="0" step="0.01">
                    <span class="input-group-text">ج.م</span>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">ملاحظات</label>
                <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success flex-grow-1"><i class="fas fa-save me-1"></i>حفظ</button>
                <a href="{{ route('treasuries.index') }}" class="btn btn-secondary flex-grow-1">إلغاء</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
