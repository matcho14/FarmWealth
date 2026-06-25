@extends('components.master')
@section('title','تعديل فاتورة بيع')
@section('content')
<div class="row"><div class="col-12">
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-file-invoice me-2"></i>تعديل فاتورة بيع رقم {{ $saleInvoice->invoice_number }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('sale-invoices.update', $saleInvoice) }}" method="POST" id="invoiceForm">
            @csrf
            @method('PATCH')
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">رقم الفاتورة <span class="text-danger">*</span></label>
                    <input type="text" name="invoice_number" class="form-control @error('invoice_number') is-invalid @enderror" value="{{ old('invoice_number', $saleInvoice->invoice_number) }}" required>
                    @error('invoice_number')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">تاريخ الفاتورة <span class="text-danger">*</span></label>
                    <input type="date" name="invoice_date" class="form-control" value="{{ old('invoice_date', $saleInvoice->invoice_date->toDateString()) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">العميل <span class="text-danger">*</span></label>
                    <select name="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
                        <option value="">اختر العميل</option>
                        @foreach($clients as $c)
                            <option value="{{ $c->id }}" {{ old('client_id', $saleInvoice->client_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('client_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="card border-primary mb-4">
                <div class="card-header bg-light">
                    <strong>أصناف الفاتورة</strong>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0" id="itemsTable">
                        <thead class="table-secondary text-center">
                            <tr>
                                <th style="width:40%">الصنف</th>
                                <th style="width:15%">الكمية</th>
                                <th style="width:20%">سعر الوحدة</th>
                                <th style="width:20%">الإجمالي</th>
                                <th style="width:5%"></th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody">
                            @foreach($saleInvoice->items as $i => $row)
                            <tr class="item-row">
                                <td>
                                    <select name="items[{{ $i }}][item_id]" class="form-select form-select-sm item-select" required>
                                        <option value="">اختر الصنف</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}" data-price="{{ $item->last_purchase_price ?? 0 }}" {{ old('items.'.$i.'.item_id', $row->item_id) == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="items[{{ $i }}][quantity]" class="form-control form-control-sm quantity-input" min="0.001" step="0.001" value="{{ old('items.'.$i.'.quantity', $row->quantity) }}" oninput="calcItemTotal(this)"></td>
                                <td><input type="number" name="items[{{ $i }}][unit_price]" class="form-control form-control-sm price-input" min="0" step="0.01" value="{{ old('items.'.$i.'.unit_price', $row->unit_price) }}" oninput="calcItemTotal(this)"></td>
                                <td class="item-total">{{ format_number($row->total, 2) }}</td>
                                <td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-item"><i class="fas fa-times"></i></button></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light fw-bold text-center">
                            <tr>
                                <td colspan="3">الإجمالي الكلي</td>
                                <td id="grandTotal">{{ format_number($saleInvoice->total_amount, 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-footer text-start">
                    <button type="button" id="addItem" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-plus me-1"></i>إضافة صنف
                    </button>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">المبلغ المدفوع</label>
                    <input type="number" name="paid_amount" class="form-control" min="0" step="0.01" value="{{ old('paid_amount', $saleInvoice->paid_amount) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">الخزنة (للمدفوعات)</label>
                    <select name="treasury_id" class="form-select">
                        <option value="">بدون خزنة</option>
                        @foreach($treasuries as $t)
                            <option value="{{ $t->id }}" {{ old('treasury_id', $saleInvoice->treasury_id) == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">ملاحظات</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $saleInvoice->notes) }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success btn-lg flex-grow-1"><i class="fas fa-save me-2"></i>تحديث الفاتورة</button>
                <a href="{{ route('sale-invoices.index') }}" class="btn btn-secondary btn-lg flex-grow-1">إلغاء</a>
            </div>
        </form>
    </div>
</div>
</div></div>

@section('scripts')
<script>
let itemIdx = {{ $saleInvoice->items->count() }};
const items = @json($items->map(fn($item)=>['id'=>$item->id,'name'=>$item->name,'price'=>$item->last_purchase_price ?? 0]));

function calcItemTotal(element) {
    const row = element.closest('.item-row');
    const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
    const price = parseFloat(row.querySelector('.price-input').value) || 0;
    const total = qty * price;
    row.querySelector('.item-total').textContent = new Intl.NumberFormat('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}).format(total);
    calcGrandTotal();
}

function calcGrandTotal() {
    let total = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        total += qty * price;
    });
    document.getElementById('grandTotal').textContent = new Intl.NumberFormat('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}).format(total);
}

document.addEventListener('change', function(e) {
    if (e.target.classList.contains('item-select')) {
        const row = e.target.closest('.item-row');
        const selected = e.target.selectedOptions[0];
        if (selected && selected.dataset.price) {
            row.querySelector('.price-input').value = selected.dataset.price;
            calcItemTotal(row);
        }
    }
});

document.getElementById('addItem').addEventListener('click', function() {
    const tbody = document.getElementById('itemsBody');
    const row = document.createElement('tr');
    row.className = 'item-row';
    row.innerHTML = `
        <td>
            <select name="items[${itemIdx}][item_id]" class="form-select form-select-sm item-select" required>
                <option value="">اختر الصنف</option>
                ${items.map(i=>`<option value="${i.id}" data-price="${i.price}">${i.name}</option>`).join('')}
            </select>
        </td>
        <td><input type="number" name="items[${itemIdx}][quantity]" class="form-control form-control-sm quantity-input" min="0.001" step="0.001" value="1" oninput="calcItemTotal(this)"></td>
        <td><input type="number" name="items[${itemIdx}][unit_price]" class="form-control form-control-sm price-input" min="0" step="0.01" value="0" oninput="calcItemTotal(this)"></td>
        <td class="item-total">0</td>
        <td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-item"><i class="fas fa-times"></i></button></td>`;
    tbody.appendChild(row);
    itemIdx++;
    calcGrandTotal();
});

document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-item')) {
        e.target.closest('.item-row').remove();
        calcGrandTotal();
    }
});
</script>
@endsection
@endsection
