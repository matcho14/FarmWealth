@extends('components.master')

@section('title', 'إنشاء عنبر جديد')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">إنشاء عنبر جديد</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('sheds.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">اسم العنبر <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            class="form-control @error('name') is-invalid @enderror"
                            id="name"
                            name="name"
                            placeholder="مثال: عنبر 1"
                            value="{{ old('name') }}"
                            required>
                        @error('name')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="floors" class="form-label">عدد الأدوار <span class="text-danger">*</span></label>
                        <input
                            type="number"
                            class="form-control @error('floors') is-invalid @enderror"
                            id="floors"
                            name="floors"
                            placeholder="مثال: 2"
                            min="1"
                            max="10"
                            value="{{ old('floors', 1) }}"
                            required>
                        <small class="text-muted">أدخل عدد الأدوار في هذا العنبر (1 إلى 10)</small>
                        @error('floors')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">الوصف</label>
                        <textarea
                            class="form-control @error('description') is-invalid @enderror"
                            id="description"
                            name="description"
                            rows="3"
                            placeholder="أضف وصفاً للعنبر (اختياري)">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success flex-grow-1">
                            <i class="fas fa-save me-2"></i>حفظ
                        </button>
                        <a href="{{ route('sheds.index') }}" class="btn btn-secondary flex-grow-1">
                            <i class="fas fa-times me-2"></i>إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
