@extends('components.master')
@section('title','قيد جديد')
@section('content')
<div class="row"><div class="col-12">
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-exchange-alt me-2"></i>إنشاء قيد محاسبي يدوي</h5>
        <small>يستخدم لتحويل رصيد بين الخزائن أو أي قيد يدوي</small>
    </div>
    <div class="card-body">
        @if($errors->has('lines'))
            <div class="alert alert-danger">{{ $errors->first('lines') }}</div>
        @endif
        <form action="{{ route('journal-entries.store') }}" method="POST">
            @csrf
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">رقم القيد (تلقائي)</label>
                    <input type="text" class="form-control bg-light" value="{{ $nextNumber }}" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label">تاريخ القيد <span class="text-danger">*</span></label>
                    <input type="date" name="entry_date" class="form-control" value="{{ old('entry_date', now()->toDateString()) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">البيان <span class="text-danger">*</span></label>
                    <input type="text" name="description" class="form-control @error('description') is-invalid @enderror"
                        value="{{ old('description') }}" placeholder="مثال: تحويل نقدي من الخزنة الرئيسية إلى خزنة الفرع" required>
                    @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>

            {{-- جدول سطور القيد --}}
            <div class="card border-primary mb-4">
                <div class="card-header bg-light">
                    <strong>سطور القيد</strong>
                    <small class="text-muted ms-2">(يجب أن يتساوى مجموع المدين مع مجموع الدائن)</small>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0" id="linesTable">
                        <thead class="table-secondary text-center">
                            <tr>
                                <th style="width:20%">نوع الحساب</th>
                                <th style="width:25%">الحساب</th>
                                <th style="width:18%">مدين</th>
                                <th style="width:18%">دائن</th>
                                <th style="width:14%">البيان</th>
                                <th style="width:5%"></th>
                            </tr>
                        </thead>
                        <tbody id="linesBody">
                            @for($i=0;$i<2;$i++)
                            <tr class="line-row">
                                 <td>
                                     <select name="lines[{{ $i }}][account_type]" class="form-select form-select-sm account-type" required>
                                         <option value="treasury">خزنة</option>
                                         <option value="supplier">مورد</option>
                                         <option value="client">عميل</option>
                                         <option value="chart_of_account">حساب من شجرة الحسابات</option>
                                         <option value="cycle">دورة</option>
                                         <option value="sales">مبيعات</option>
                                     </select>
                                 </td>
                                 <td>
                                     <select name="lines[{{ $i }}][account_id]" class="form-select form-select-sm account-id" required>
                                         @foreach($treasuries as $t)
                                             <option value="{{ $t->id }}" data-type="treasury">{{ $t->name }}</option>
                                         @endforeach
                                         @foreach($suppliers as $s)
                                             <option value="{{ $s->id }}" data-type="supplier" style="display:none">{{ $s->name }}</option>
                                         @endforeach
                                         @foreach($clients as $c)
                                             <option value="{{ $c->id }}" data-type="client" style="display:none">{{ $c->name }}</option>
                                         @endforeach
                                         @foreach($chartAccounts as $acc)
                                             <option value="{{ $acc->id }}" data-type="chart_of_account" style="display:none">{{ $acc->full_code }} - {{ $acc->name }}</option>
                                         @endforeach
                                     </select>
                                 </td>
                                <td><input type="number" name="lines[{{ $i }}][debit]" class="form-control form-control-sm debit-input" min="0" step="0.01" value="0" oninput="calcTotals()"></td>
                                <td><input type="number" name="lines[{{ $i }}][credit]" class="form-control form-control-sm credit-input" min="0" step="0.01" value="0" oninput="calcTotals()"></td>
                                <td><input type="text" name="lines[{{ $i }}][description]" class="form-control form-control-sm" placeholder="بيان"></td>
                                <td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-line" style="display:none"><i class="fas fa-times"></i></button></td>
                            </tr>
                            @endfor
                        </tbody>
                        <tfoot class="table-light fw-bold text-center">
                            <tr>
                                <td colspan="2">الإجمالي</td>
                                <td class="text-success" id="totalDebit">0</td>
                                <td class="text-danger" id="totalCredit">0</td>
                                <td colspan="2">
                                    <span id="balanceStatus" class="badge bg-secondary">-</span>
                                </td>
                            </tr>
                            <tr><td colspan="6" class="p-2 text-start">
                                <button type="button" id="addLine" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-plus me-1"></i>إضافة سطر
                                </button>
                            </td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">ملاحظات</label>
                <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success btn-lg flex-grow-1"><i class="fas fa-save me-2"></i>حفظ القيد</button>
                <a href="{{ route('journal-entries.index') }}" class="btn btn-secondary btn-lg flex-grow-1">إلغاء</a>
            </div>
        </form>
    </div>
</div>
</div></div>

@section('scripts')
<script>
let lineIdx = 2;
const treasuries = @json($treasuries->map(fn($t)=>['id'=>$t->id,'name'=>$t->name]));
const suppliers  = @json($suppliers->map(fn($s)=>['id'=>$s->id,'name'=>$s->name]));
const clients    = @json($clients->map(fn($c)=>['id'=>$c->id,'name'=>$c->name]));
const chartAccounts = @json($chartAccounts->map(fn($a)=>['id'=>$a->id,'name'=>$a->full_code . ' - ' . $a->name]));

function buildAccountOptions(type) {
    if (type === 'treasury') return treasuries.map(i=>`<option value="${i.id}">${i.name}</option>`).join('');
    if (type === 'supplier') return suppliers.map(i=>`<option value="${i.id}">${i.name}</option>`).join('');
    if (type === 'client') return clients.map(i=>`<option value="${i.id}">${i.name}</option>`).join('');
    if (type === 'chart_of_account') return chartAccounts.map(i=>`<option value="${i.id}">${i.name}</option>`).join('');
    if (type === 'cycle') return '<option value="">اختر الدورة من النموذج</option>';
    if (type === 'sales') return '<option value="">حساب المبيعات</option>';
    return '';
}

document.addEventListener('change', function(e) {
    if (e.target.classList.contains('account-type')) {
        const row     = e.target.closest('.line-row');
        const type    = e.target.value;
        const sel     = row.querySelector('.account-id');
        sel.innerHTML = buildAccountOptions(type);
    }
});

document.getElementById('addLine').addEventListener('click', function() {
    const tbody = document.getElementById('linesBody');
    const row   = document.createElement('tr');
    row.className = 'line-row';
    row.innerHTML = `
        <td><select name="lines[${lineIdx}][account_type]" class="form-select form-select-sm account-type" required>
            <option value="treasury">خزنة</option><option value="supplier">مورد</option>
            <option value="client">عميل</option><option value="chart_of_account">حساب من شجرة الحسابات</option>
            <option value="cycle">دورة</option><option value="sales">مبيعات</option>
        </select></td>
        <td><select name="lines[${lineIdx}][account_id]" class="form-select form-select-sm account-id" required>
            ${buildAccountOptions('treasury')}
        </select></td>
        <td><input type="number" name="lines[${lineIdx}][debit]"  class="form-control form-control-sm debit-input"  min="0" step="0.01" value="0" oninput="calcTotals()"></td>
        <td><input type="number" name="lines[${lineIdx}][credit]" class="form-control form-control-sm credit-input" min="0" step="0.01" value="0" oninput="calcTotals()"></td>
        <td><input type="text"   name="lines[${lineIdx}][description]" class="form-control form-control-sm" placeholder="بيان"></td>
        <td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-line"><i class="fas fa-times"></i></button></td>`;
    tbody.appendChild(row);
    lineIdx++;
    updateRemoveButtons();
});

document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-line')) {
        e.target.closest('.line-row').remove();
        calcTotals();
        updateRemoveButtons();
    }
});

function calcTotals() {
    let d=0, c=0;
    document.querySelectorAll('.debit-input').forEach(el  => d += parseFloat(el.value)||0);
    document.querySelectorAll('.credit-input').forEach(el => c += parseFloat(el.value)||0);
    const formatter = new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 2
    });
    document.getElementById('totalDebit').textContent  = formatter.format(d);
    document.getElementById('totalCredit').textContent = formatter.format(c);
    const bs = document.getElementById('balanceStatus');
    if (Math.abs(d-c) < 0.01) { bs.textContent='متوازن ✓'; bs.className='badge bg-success'; }
    else                        { bs.textContent='غير متوازن ✗'; bs.className='badge bg-danger'; }
}

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.line-row');
    rows.forEach(r => { r.querySelector('.remove-line').style.display = rows.length>2?'inline-block':'none'; });
}
</script>
@endsection
@endsection
