@extends('components.master')

@section('title', 'تعديل مبيعات')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">تعديل مبيعات الدورة #{{ $cycle->id }}</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-4">
                    الكتاكيت المتبقية المتاحة بعد تعديل هذه الحركة: <strong>{{ $cycle->expected_remaining }}</strong>
                </div>

                <form action="{{ route('cycles.updateSale', [$cycle, $financialRecord]) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="payment_type" class="form-label fw-bold">نوع الدفع <span class="text-danger">*</span></label>
                            <select class="form-select @error('payment_type') is-invalid @enderror" id="payment_type" name="payment_type" required onchange="togglePaymentFields()">
                                <option value="">-- اختر نوع الدفع --</option>
                                <option value="cash" {{ old('payment_type', $financialRecord->payment_type) === 'cash' ? 'selected' : '' }}>كاش (تحصيل فوري للخزنة)</option>
                                <option value="credit" {{ old('payment_type', $financialRecord->payment_type) === 'credit' ? 'selected' : '' }}>إجل (سداد لاحق)</option>
                            </select>
                            @error('payment_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div id="treasury-field" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="treasury_id" class="form-label fw-bold">الخزنة <span class="text-danger">*</span></label>
                                <select class="form-select @error('treasury_id') is-invalid @enderror" id="treasury_id" name="treasury_id">
                                    <option value="">-- اختر الخزنة --</option>
                                    @foreach($treasuries as $treasury)
                                        <option value="{{ $treasury->id }}" {{ old('treasury_id', $financialRecord->treasury_id) == $treasury->id ? 'selected' : '' }}>
                                            {{ $treasury->name }} (الرصيد: {{ format_number($treasury->balance, 2) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('treasury_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="client_id" class="form-label fw-bold">العميل <span class="text-danger">*</span></label>
                            <select class="form-select @error('client_id') is-invalid @enderror" id="client_id" name="client_id" required>
                                <option value="">-- اختر العميل --</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id', $financialRecord->client_id) == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }} (الرصيد الحالي: {{ format_number($client->balance, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="sold_count" class="form-label">عدد الكتاكيت المباعة <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('sold_count') is-invalid @enderror" id="sold_count" name="sold_count" value="{{ old('sold_count', $financialRecord->quantity) }}" min="1" required>
                            @error('sold_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="weight" class="form-label">إجمالي الوزن (كيلو) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight', $financialRecord->weight) }}" min="0.1" required>
                            @error('weight')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="amount" class="form-label">إجمالي مبلغ البيع <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount', $financialRecord->amount) }}" min="0" required>
                            @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="record_date" class="form-label fw-bold">تاريخ البيع <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('record_date') is-invalid @enderror" id="record_date" name="record_date" value="{{ old('record_date', $financialRecord->record_date->toDateString()) }}" required>
                            @error('record_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="description" class="form-label fw-bold">وصف إضافي (اختياري)</label>
                            <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description', $financialRecord->description) }}" placeholder="مثال: بيع للعميل أحمد">
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-2"></i>تحديث المبيعات
                        </button>
                        <a href="{{ route('cycles.show', $cycle) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function togglePaymentFields() {
    const type = document.getElementById('payment_type').value;
    const treasuryField = document.getElementById('treasury-field');
    treasuryField.style.display = type === 'cash' ? 'block' : 'none';
    document.getElementById('treasury_id').required = type === 'cash';
}

document.addEventListener('DOMContentLoaded', function() {
    togglePaymentFields();
});
</script>
@endsection
