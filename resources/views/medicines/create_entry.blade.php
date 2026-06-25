@extends('components.master')

@section('title', 'إضافة كمية للمخزن')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="page-header mb-4 text-dark">
            <h1><i class="fas fa-truck-loading me-2 text-primary"></i> إضافة كمية للمخزن</h1>
            <p class="mb-0">الصنف: <strong>{{ $medicine->name }}</strong></p>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-2">
                    <li class="breadcrumb-item"><a href="{{ route('medicines.index') }}">دليل الأدوية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('medicines.show', $medicine) }}">كارت الصنف</a></li>
                    <li class="breadcrumb-item active">إضافة كمية</li>
                </ol>
            </nav>
        </div>
        
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 text-dark">
                <form action="{{ route('medicines.entries.store', $medicine) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="quantity" class="form-label fw-bold">الكمية الواردة ({{ $medicine->unit }})</label>
                            <input type="number" step="0.01" class="form-control form-control-lg @error('quantity') is-invalid @enderror" id="quantity" name="quantity" required>
                            @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="price" class="form-label fw-bold">سعر الوحدة (جنيه)</label>
                            <input type="number" step="0.01" class="form-control form-control-lg @error('price') is-invalid @enderror" id="price" name="price" required>
                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="entry_date" class="form-label fw-bold">تاريخ الدخول</label>
                        <input type="date" class="form-control form-control-lg @error('entry_date') is-invalid @enderror" id="entry_date" name="entry_date" value="{{ date('Y-m-d') }}" required>
                        @error('entry_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4">
                        <i class="fas fa-info-circle fa-lg me-2"></i>
                        <span>سيتم إدراج هذه الكمية ضمن نظام FIFO المخزني.</span>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg shadow-sm">
                            <i class="fas fa-plus-circle me-1"></i> تأكيد الإدخال للمخزن
                        </button>
                        <a href="{{ route('medicines.show', $medicine) }}" class="btn btn-light">العودة</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
