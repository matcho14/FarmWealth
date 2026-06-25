@extends('components.master')
@section('title', 'تحويل مخزني')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0"><i class="fas fa-exchange-alt me-2"></i>تحويل من المخزن الرئيسي إلى مخزن العنبر</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('inventory.transfer.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">الصنف المراد تحويله <span class="text-danger">*</span></label>
                            <select name="item_id" class="form-select @error('item_id') is-invalid @enderror" required id="item_select">
                                <option value="">-- اختر الصنف --</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}" data-qty="{{ $item->quantity_in_stock }}" data-unit="{{ $item->unit }}">
                                        {{ $item->name }} (المتاح: {{ format_number($item->quantity_in_stock, 3) }} {{ $item->unit }})
                                    </option>
                                @endforeach
                            </select>
                            <div id="item_info" class="form-text mt-2 text-primary d-none"></div>
                            @error('item_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">العنبر المستلم <span class="text-danger">*</span></label>
                            <select name="shed_id" class="form-select @error('shed_id') is-invalid @enderror" required>
                                <option value="">-- اختر العنبر --</option>
                                @foreach($sheds as $shed)
                                    <option value="{{ $shed->id }}">{{ $shed->name }}</option>
                                @endforeach
                            </select>
                            @error('shed_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">الكمية المحولة <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" 
                                    step="0.001" min="0.001" required value="{{ old('quantity') }}">
                                <span class="input-group-text unit_label">الوحدة</span>
                            </div>
                            @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">تاريخ التحويل <span class="text-danger">*</span></label>
                            <input type="date" name="transfer_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">ملاحظات</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="أضف أي ملاحظات إضافية هنا...">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-success btn-lg flex-grow-1">
                            <i class="fas fa-check me-2"></i>إتمام التحويل
                        </button>
                        <a href="{{ route('inventory.index') }}" class="btn btn-light btn-lg border px-4">
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
        info.innerHTML = `<i class="fas fa-info-circle me-1"></i> الكمية المتاحة في المخزن الرئيسي: <strong>${qty} ${unit}</strong>`;
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
