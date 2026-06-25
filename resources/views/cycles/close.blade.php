@extends('components.master')

@section('title', 'إغلاق الدورة')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">إغلاق الدورة #{{ $cycle->id }}</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning mb-3">
                    <strong><i class="fas fa-exclamation-triangle me-2"></i>تحذير:</strong><br>
                    عند إغلاق الدورة، لن تتمكن من تعديل السجلات المالية أو إضافة نافق جديد.
                </div>

                <div class="alert alert-info mb-3">
                    <strong>ملخص الدورة:</strong><br>
                    الكتاكيت الأولي: <strong>{{ $cycle->initial_chicks }}</strong><br>
                    النافق: <strong>{{ $cycle->mortality_count }}</strong><br>
                    <strong class="text-success">المتوقع المتبقي: {{ $cycle->expected_remaining }}</strong>
                </div>

                <form action="{{ route('cycles.closeCycle', $cycle) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="sold_chicks" class="form-label">عدد الكتاكيت المباعة الفعلي <span class="text-danger">*</span></label>
                            <input
                                type="number"
                                class="form-control @error('sold_chicks') is-invalid @enderror"
                                id="sold_chicks"
                                name="sold_chicks"
                                placeholder="مثال: 940"
                                min="0"
                                max="{{ $cycle->expected_remaining }}"
                                value="{{ old('sold_chicks', $cycle->expected_remaining) }}"
                                required>
                            @error('sold_chicks')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="sold_weight" class="form-label">إجمالي وزن هذه الكمية (كيلو) <span class="text-danger">*</span></label>
                            <input
                                type="number"
                                step="0.01"
                                class="form-control @error('sold_weight') is-invalid @enderror"
                                id="sold_weight"
                                name="sold_weight"
                                placeholder="مثال: 1850.5"
                                min="0"
                                value="{{ old('sold_weight') }}"
                                required>
                            @error('sold_weight')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <small class="text-muted d-block mb-3">الحد الأقصى للكتاكيت: {{ $cycle->expected_remaining }}</small>

                    <div class="alert alert-secondary" id="discrepancy-alert" style="display:none;">
                        <strong>الفرق المتوقع:</strong> <span id="discrepancy-value"></span>
                        <p class="mb-0 mt-2"><small id="discrepancy-message"></small></p>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger flex-grow-1">
                            <i class="fas fa-times-circle me-2"></i>إغلاق الدورة
                        </button>
                        <a href="{{ route('cycles.show', $cycle) }}" class="btn btn-secondary flex-grow-1">
                            <i class="fas fa-times me-2"></i>إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('sold_chicks').addEventListener('input', function() {
    const sold = parseInt(this.value) || 0;
    const expected = {{ $cycle->expected_remaining }};
    const discrepancy = expected - sold;

    const alert = document.getElementById('discrepancy-alert');
    const value = document.getElementById('discrepancy-value');
    const message = document.getElementById('discrepancy-message');

    if (this.value !== '') {
        value.textContent = discrepancy;

        if (discrepancy === 0) {
            message.textContent = 'لا توجد فروقات - جميع الكتاكيت مسجلة';
            alert.className = 'alert alert-success';
        } else if (discrepancy > 0) {
            message.textContent = `خسائر غير مسجلة: ${discrepancy} كتكوتة`;
            alert.className = 'alert alert-warning';
        } else {
            message.textContent = `تم بيع أكثر من المتوقع: ${Math.abs(discrepancy)} كتكوتة`;
            alert.className = 'alert alert-danger';
        }

        alert.style.display = 'block';
    } else {
        alert.style.display = 'none';
    }
});
</script>
@endsection
