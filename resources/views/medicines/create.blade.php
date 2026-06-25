@extends('components.master')

@section('title', 'إضافة دواء جديد')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="page-header mb-4">
            <h1><i class="fas fa-plus-circle me-2 text-primary"></i> إضافة صنف دواء جديد</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('medicines.index') }}">دليل الأدوية</a></li>
                    <li class="breadcrumb-item active">إضافة صنف</li>
                </ol>
            </nav>
        </div>
        
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 text-dark">
                <form action="{{ route('medicines.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="form-label fw-bold">اسم الدواء</label>
                        <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name" name="name" placeholder="مثال: لاسوتا، مضاد حيوي" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="unit" class="form-label fw-bold">وحدة القياس</label>
                        <select class="form-select form-select-lg @error('unit') is-invalid @enderror" id="unit" name="unit">
                            <option value="">-- اختر وحدة القياس --</option>
                            @foreach($units as $u)
                                <option value="{{ $u->name }}" {{ old('unit') == $u->name ? 'selected' : '' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">الوحدة التي سيتم التعامل بها في الصرف والمخزن.</small>
                        @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">الوصف أو الملاحظات</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="تاريخ الصلاحية، طريقة الاستخدام، إلخ..."></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                            <i class="fas fa-save me-1"></i> حفظ بيانات الصنف
                        </button>
                        <a href="{{ route('medicines.index') }}" class="btn btn-light">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
