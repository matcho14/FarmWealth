@extends('components.master')
@section('title','صنف جديد')
@section('content')
<div class="row justify-content-center"><div class="col-md-6">
<div class="card">
    <div class="card-header"><h5 class="mb-0"><i class="fas fa-box me-2"></i>إضافة صنف جديد</h5></div>
    <div class="card-body">
        <form action="{{ route('items.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">اسم الصنف <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" required>
                @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">تصنيف الصنف <span class="text-danger">*</span></label>
                <select name="category" class="form-select">
                    <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>عام</option>
                    <option value="feed" {{ old('category') == 'feed' ? 'selected' : '' }}>علف</option>
                    <option value="medicine" {{ old('category') == 'medicine' ? 'selected' : '' }}>أدوية/تحصينات</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">وحدة القياس <span class="text-danger">*</span></label>
                <select name="unit" class="form-select">
                    @foreach($units as $u)
                        <option value="{{ $u->name }}" {{ old('unit') === $u->name ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">المورد الافتراضي</label>
                <select name="supplier_id" class="form-select">
                    <option value="">-- بدون مورد --</option>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">ملاحظات</label>
                <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success flex-grow-1"><i class="fas fa-save me-1"></i>حفظ</button>
                <a href="{{ route('items.index') }}" class="btn btn-secondary flex-grow-1">إلغاء</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
