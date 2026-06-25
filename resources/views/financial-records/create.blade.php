@extends('components.master')

@section('title', 'إضافة سجل مالي')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">إضافة سجل مالي - الدورة #{{ $cycle->id }} ({{ $cycle->shed->name ?? 'غير محدد' }})</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('financial-records.store', $cycle) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="type" class="form-label fw-bold">نوع السجل <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">-- اختر --</option>
                            <option value="expense" {{ old('type') === 'expense' ? 'selected' : '' }}>مصروف</option>
                            <option value="revenue" {{ old('type') === 'revenue' ? 'selected' : '' }}>إيراد</option>
                        </select>
                        @error('type')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="treasury_id" class="form-label fw-bold">الخزينة المتأثرة <span class="text-danger">*</span></label>
                        <select class="form-select @error('treasury_id') is-invalid @enderror" id="treasury_id" name="treasury_id" required>
                            <option value="">-- اختر الخزينة --</option>
                            @foreach($treasuries as $treasury)
                                <option value="{{ $treasury->id }}" {{ old('treasury_id') == $treasury->id ? 'selected' : '' }}>
                                    {{ $treasury->name }} (الرصيد الحالي: {{ format_number($treasury->balance, 2) }})
                                </option>
                            @endforeach
                        </select>
                        @error('treasury_id')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="chart_of_account_id" class="form-label fw-bold">حساب من شجرة الحسابات (اختياري)</label>
                        <select class="form-select select2 @error('chart_of_account_id') is-invalid @enderror" id="chart_of_account_id" name="chart_of_account_id">
                            <option value="">-- اختر الحساب --</option>
                            @foreach($chartAccounts as $account)
                                <option value="{{ $account->id }}" {{ old('chart_of_account_id') == $account->id ? 'selected' : '' }}>
                                    {{ $account->full_code }} - {{ $account->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('chart_of_account_id')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">يمكنك ربط هذا السجل بحساب محاسبي محدد من شجرة الحسابات (مثل مصروف كهرباء)</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="amount" class="form-label">المبلغ <span class="text-danger">*</span></label>
                            <input
                                type="number"
                                step="0.01"
                                class="form-control @error('amount') is-invalid @enderror"
                                id="amount"
                                name="amount"
                                placeholder="0.00"
                                min="0.01"
                                value="{{ old('amount') }}"
                                required>
                            @error('amount')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="floor_number" class="form-label">رقم الدور (اختياري)</label>
                            <input
                                type="number"
                                class="form-control @error('floor_number') is-invalid @enderror"
                                id="floor_number"
                                name="floor_number"
                                min="1"
                                value="{{ old('floor_number') }}"
                                placeholder="رقم الدور">
                            @error('floor_number')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">الوصف <span class="text-danger">*</span></label>
                        <textarea
                            class="form-control @error('description') is-invalid @enderror"
                            id="description"
                            name="description"
                            rows="3"
                            placeholder="مثال: فاتورة كهرباء العنبر"
                            required>{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="record_date" class="form-label">التاريخ <span class="text-danger">*</span></label>
                        <input
                            type="date"
                            class="form-control @error('record_date') is-invalid @enderror"
                            id="record_date"
                            name="record_date"
                            value="{{ old('record_date', now()->toDateString()) }}"
                            required>
                        @error('record_date')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <input type="hidden" name="cycle_id" value="{{ $cycle->id }}">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success flex-grow-1">
                            <i class="fas fa-save me-2"></i>حفظ
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
@endsection
