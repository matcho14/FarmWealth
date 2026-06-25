@extends('components.master')
@section('title','تعديل فاتورة شراء')
@section('content')
<div class="row"><div class="col-12">
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-file-invoice me-2"></i>تعديل فاتورة شراء رقم {{ $purchaseInvoice->invoice_number }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('purchase-invoices.update', $purchaseInvoice) }}" method="POST" id="invoiceForm">
            @csrf
            @method('PATCH')

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">رقم الفاتورة <span class="text-danger">*</span></label>
                    <input type="text" name="invoice_number" class="form-control @error('invoice_number') is-invalid @enderror" value="{{ old('invoice_number', $purchaseInvoice->invoice_number) }}" required>
                    @error('invoice_number')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">المورد <span class="text-danger">*</span></label>
                    <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                        <option value="">-- اختر المورد --</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" {{ old('supplier_id', $purchaseInvoice->supplier_id) == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                    @error('supplier_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">تاريخ الفاتورة <span class="text-danger">*</span></label>
                    <input type="date" name="invoice_date" class="form-control" value="{{ old('invoice_date', $purchaseInvoice->invoice_date->toDateString()) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">ملاحظات</label>
                    <input type="text" name="notes" class="form-control" value="{{ old('notes', $purchaseInvoice->notes) }}">
                </div>
            </div>

            <div class="card mb-4 border-primary">
                <div class="card-header bg-light fw-bold">
                    <i class="fas fa-boxes me-2"></i>الأصناف المشتراة
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0" id="itemsTable">
                            <thead class="table-secondary text-center">
                                <tr>
                                    <th style="width:35%">الصنف</th>
                                    <th style="width:15%">الكمية</th>
                                    <th style="width:15%">سعر الوحدة</th>
                                    <th style="width:20%">الإجمالي</th>
                                    <th style="width:15%">حذف</th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                @foreach($purchaseInvoice->items as $i => $row)
                                <tr class="item-row">
                                    <td>
                                        <select name="items[{{ $i }}][item_id]" class="form-select form-select-sm item-select" required>
                                            <option value="">-- اختر صنف --</option>
                                            @foreach($items as $item)
                                                <option value="{{ $item->id }}" {{ old('items.'.$i.'.item_id', $row->item_id) == $item->id ? 'selected' : '' }}>{{ $item->name }} ({{ $item->unit }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" name="items[{{ $i }}][quantity]" class="form-control form-control-sm qty-input" min="0.001" step="0.001" value="{{ old('items.'.$i.'.quantity', $row->quantity) }}" required></td>
                                    <td><input type="number" name="items[{{ $i }}][unit_price]" class="form-control form-control-sm price-input" min="0" step="0.01" value="{{ old('items.'.$i.'.unit_price', $row->unit_price) }}" required></td>
                                    <td class="text-center fw-bold line-total">{{ format_number($row->total, 2) }}</td>
                                    <td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-times"></i></button></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="p-2">
                                        <button type="button" id="addRow" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-plus me-1"></i>إضافة صنف
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <div class="card border-dark text-center p-3">
                        <small class="text-muted">إجمالي الفاتورة</small>
                        <h3 class="text-primary mb-0" id="grandTotal">{{ format_number($purchaseInvoice->total_amount, 2) }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">المبلغ المدفوع الآن</label>
                    <div class="input-group">
                        <input type="number" name="paid_amount" class="form-control" min="0" step="0.01" value="{{ old('paid_amount', $purchaseInvoice->paid_amount) }}">
                        <span class="input-group-text">ج.م</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">الخزنة المدفوع منها</label>
                    <select name="treasury_id" class="form-select">
                        <option value="">-- لا يوجد دفع --</option>
                        @foreach($treasuries as $t)
                            <option value="{{ $t->id }}" {{ old('treasury_id', $purchaseInvoice->treasury_id) == $t->id ? 'selected' : '' }}>
                                {{ $t->name }} ({{ format_number($t->balance,2) }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-success btn-lg flex-grow-1">
                    <i class="fas fa-save me-2"></i>تحديث الفاتورة
                </button>
                <a href="{{ route('purchase-invoices.index') }}" class="btn btn-secondary btn-lg flex-grow-1">إلغاء</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>

@section('scripts')
<script>
let rowIndex = {{ $purchaseInvoice->items->count() }};
const itemsHtml = `@foreach($items as $item)<option value="{{ $item->id }}">{{ $item->name }} ({{ $item->unit }})</option>@endforeach`;

function calcRow(row) {
    const qty   = parseFloat(row.querySelector('.qty-input').value)   || 0;
    const price = parseFloat(row.querySelector('.price-input').value) || 0;
    const formatter = new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 2
    });
    row.querySelector('.line-total').textContent = formatter.format(qty * price);
    calcGrand();
}

function calcGrand() {
    let total = 0;
    document.querySelectorAll('.line-total').forEach(el => total += parseFloat(el.textContent) || 0);
    const formatter = new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 2
    });
    document.getElementById('grandTotal').textContent = formatter.format(total);
}

document.getElementById('addRow').addEventListener('click', function() {
    const tbody = document.getElementById('itemsBody');
    const newRow = document.createElement('tr');
    newRow.className = 'item-row';
    newRow.innerHTML = `
        <td><select name="items[${rowIndex}][item_id]" class="form-select form-select-sm item-select" required>
            <option value="">-- اختر صنف --</option>${itemsHtml}
        </select></td>
        <td><input type="number" name="items[${rowIndex}][quantity]" class="form-control form-control-sm qty-input" min="0.001" step="0.001" value="1" required></td>
        <td><input type="number" name="items[${rowIndex}][unit_price]" class="form-control form-control-sm price-input" min="0" step="0.01" value="0" required></td>
        <td class="text-center fw-bold line-total">0</td>
        <td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-times"></i></button></td>
    `;
    tbody.appendChild(newRow);
    rowIndex++;
});

document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-row')) {
        e.target.closest('.item-row').remove();
        calcGrand();
    }
});

document.addEventListener('input', function(e) {
    if (e.target.classList.contains('qty-input') || e.target.classList.contains('price-input')) {
        calcRow(e.target.closest('.item-row'));
    }
});
</script>
@endsection
@endsection
