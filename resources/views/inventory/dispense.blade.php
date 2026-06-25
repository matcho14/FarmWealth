@extends('components.master')
@section('title', 'صرف أصناف')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success text-white py-3">
                <h5 class="mb-0"><i class="fas fa-hand-holding-medical me-2"></i>صرف من مخزن العنبر إلى الدورة</h5>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-light border-start border-success border-4 mb-4">
                    <div class="row text-center">
                        <div class="col-md-4 border-end">
                            <small class="text-muted d-block">العنبر</small>
                            <span class="fw-bold">{{ $shed->name }}</span>
                        </div>
                        <div class="col-md-4 border-end">
                            <small class="text-muted d-block">تاريخ بدء الدورة</small>
                            <span class="fw-bold">{{ $cycle->start_date->format('Y-m-d') }}</span>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">عدد الكتاكيت الحالية</small>
                            <span class="fw-bold text-success">{{ format_number($cycle->expected_remaining) }}</span>
                        </div>
                    </div>
                </div>

                <form action="{{ route('inventory.dispense.store', $cycle->id) }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">الصنف المطلوب صرفه <span class="text-danger">*</span></label>
                            <select name="item_id" class="form-select @error('item_id') is-invalid @enderror" required id="item_select">
                                <option value="">-- اختر الصنف من مخزن العنبر --</option>
                                @foreach($inventory as $inv)
                                    <option value="{{ $inv->item_id }}" data-qty="{{ $inv->quantity }}" data-unit="{{ $inv->item->unit }}">
                                        {{ $inv->item->name }} (المتاح في العنبر: {{ format_number($inv->quantity, 3) }} {{ $inv->item->unit }})
                                    </option>
                                @endforeach
                            </select>
                            @if($inventory->isEmpty())
                                <div class="alert alert-warning mt-2 small">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    لا توجد أصناف في عهدة العنبر حالياً. يجب <a href="{{ route('inventory.transfer.create') }}" class="alert-link">تحويل أصناف من المخزن الرئيسي</a> أولاً.
                                </div>
                            @endif
                            <div id="item_info" class="form-text mt-2 text-success d-none"></div>
                            @error('item_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">يصرف على الدور رقم <span class="text-danger">*</span></label>
                            <select name="floor_number" class="form-select @error('floor_number') is-invalid @enderror" required>
                                <option value="">-- اختر الدور --</option>
                                @for($i = 1; $i <= $cycle->floors_count; $i++)
                                    <option value="{{ $i }}" {{ old('floor_number') == $i ? 'selected' : '' }}>الدور {{ $i }}</option>
                                @endfor
                            </select>
                            @error('floor_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">الكمية المصروفة <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" 
                                    step="0.001" min="0.001" required value="{{ old('quantity') }}">
                                <span class="input-group-text unit_label">الوحدة</span>
                            </div>
                            @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">تاريخ الصرف <span class="text-danger">*</span></label>
                            <input type="date" name="dispensation_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">ملاحظات الصرف</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="مثال: علف بادي تركيز 23% أو علاج تحصين...">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-success btn-lg flex-grow-1">
                            <i class="fas fa-check-circle me-2"></i>تسجيل الصرف على الدورة
                        </button>
                        <a href="{{ route('cycles.show', $cycle->id) }}" class="btn btn-light btn-lg border px-4">
                            إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.getElementById('item_select').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const info = document.getElementById('item_info');
    const unitLabels = document.querySelectorAll('.unit_label');
    
    if (this.value) {
        const qty = selected.getAttribute('data-qty');
        const unit = selected.getAttribute('data-unit');
        info.innerHTML = `<i class="fas fa-info-circle me-1"></i> المتاح في عهدة العنبر: <strong>${qty} ${unit}</strong>`;
        info.classList.remove('d-none');
        unitLabels.forEach(el => el.textContent = unit);
    } else {
        info.classList.add('d-none');
        unitLabels.forEach(el => el.textContent = 'الوحدة');
    }
});
</script>
@endsection
@endsection
