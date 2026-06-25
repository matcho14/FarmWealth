@extends('components.master')

@section('title', 'إنشاء دورة جديدة')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">إنشاء دورة جديدة في عنبر: {{ $shed->name }}</h5>
                <small class="text-muted">عدد الأدوار: {{ $shed->floors }}</small>
            </div>
            <div class="card-body">
                <form action="{{ route('cycles.store', $shed) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="start_date" class="form-label">تاريخ البداية <span class="text-danger">*</span></label>
                        <input
                            type="date"
                            class="form-control @error('start_date') is-invalid @enderror"
                            id="start_date"
                            name="start_date"
                            value="{{ old('start_date', now()->toDateString()) }}"
                            required>
                        @error('start_date')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- توزيع الكتاكيت على الأدوار --}}
                    <div class="card mb-3 border-primary">
                        <div class="card-header bg-primary text-white py-2">
                            <i class="fas fa-layer-group me-2"></i>
                            توزيع الكتاكيت على الأدوار
                        </div>
                        <div class="card-body">
                            @if($errors->has('floor_chicks'))
                                <div class="alert alert-danger py-2">{{ $errors->first('floor_chicks') }}</div>
                            @endif

                            @for($floor = 1; $floor <= $shed->floors; $floor++)
                                <div class="mb-3">
                                    <label for="floor_chicks_{{ $floor }}" class="form-label fw-bold">
                                        <i class="fas fa-layer-group text-primary me-1"></i>
                                        الدور {{ $floor }} <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">دور {{ $floor }}</span>
                                        <input
                                            type="number"
                                            class="form-control @error('floor_chicks.'.$floor) is-invalid @enderror"
                                            id="floor_chicks_{{ $floor }}"
                                            name="floor_chicks[{{ $floor }}]"
                                            placeholder="عدد الكتاكيت في الدور {{ $floor }}"
                                            min="0"
                                            value="{{ old('floor_chicks.'.$floor, 0) }}"
                                            oninput="updateTotal()">
                                        <span class="input-group-text">كتكوت</span>
                                    </div>
                                    @error('floor_chicks.'.$floor)
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endfor

                            <div class="alert alert-info mb-0 d-flex justify-content-between align-items-center">
                                <strong><i class="fas fa-calculator me-2"></i>الإجمالي الكلي:</strong>
                                <strong id="total_chicks" class="fs-5 text-primary">0 كتكوت</strong>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success flex-grow-1">
                            <i class="fas fa-save me-2"></i>حفظ الدورة
                        </button>
                        <a href="{{ route('sheds.show', $shed) }}" class="btn btn-secondary flex-grow-1">
                            <i class="fas fa-times me-2"></i>إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
function updateTotal() {
    let total = 0;
    document.querySelectorAll('input[name^="floor_chicks"]').forEach(function(input) {
        total += parseInt(input.value) || 0;
    });
    document.getElementById('total_chicks').textContent = total.toLocaleString('ar-EG') + ' كتكوت';
}
// حساب المجموع عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', updateTotal);
</script>
@endsection
@endsection
