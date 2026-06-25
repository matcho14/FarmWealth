@extends('components.master')
@section('title', $entityType ? 'ربط حساب بشجرة الحسابات' : 'إضافة حساب جديد')
@section('content')
<div class="page-header">
    <div>
        <h1>
            <i class="fa-solid fa-plus-circle me-2"></i>
            {{ $entityType ? 'ربط حساب بشجرة الحسابات' : 'إضافة حساب جديد' }}
        </h1>
        <p>
            {{ $entityType ? 'ربط ' . ($entityType === \App\Models\Supplier::class ? 'المورد' : 'العميل') . ' بهيكل الحسابات' : 'أدخل بيانات الحساب الجديد في شجرة الحسابات' }}
        </p>
    </div>
</div>

@if($entityType)
<div class="alert alert-info mb-3">
    <i class="fa-solid fa-circle-info me-1"></i>
    أنت تقوم بربط {{ $entityType === \App\Models\Supplier::class ? 'مورد' : 'عميل' }} موجود مسبقاً بشجرة الحسابات.
    أدخل الرمز واختر الحساب الأب المناسب ثم احفظ.
</div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('chart-of-accounts.store') }}" method="POST">
            @csrf
            @if($entityType)
                <input type="hidden" name="linkable_type" value="{{ $entityType }}">
                <input type="hidden" name="linkable_id" value="{{ $entityId }}">
            @endif

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="code" class="form-label fw-bold">رمز الحساب <span class="text-danger">*</span></label>
                    <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $suggestedCode ?? '') }}" placeholder="مثال: 5130" required maxlength="20" autocomplete="off" spellcheck="false" style="cursor: text;">
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="name" class="form-label fw-bold">اسم الحساب <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $entityName ?? '') }}" placeholder="مثال: كهرباء" required maxlength="255">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="account_type" class="form-label fw-bold">نوع الحساب <span class="text-danger">*</span></label>
                    <select name="account_type" id="account_type" class="form-select select2 @error('account_type') is-invalid @enderror" required>
                        <option value="">-- اختر النوع --</option>
                        <option value="asset_current" {{ old('account_type', ($entityType === \App\Models\Supplier::class ? '' : 'asset_current')) == 'asset_current' ? 'selected' : '' }}>أصول متداولة</option>
                        <option value="asset_fixed" {{ old('account_type') == 'asset_fixed' ? 'selected' : '' }}>أصول ثابتة</option>
                        <option value="liability_current" {{ old('account_type', ($entityType === \App\Models\Supplier::class ? 'liability_current' : '')) == 'liability_current' ? 'selected' : '' }}>خصوم متداولة</option>
                        <option value="liability_long" {{ old('account_type') == 'liability_long' ? 'selected' : '' }}>خصوم طويلة الأجل</option>
                        <option value="equity" {{ old('account_type') == 'equity' ? 'selected' : '' }}>حقوق ملكية</option>
                        <option value="revenue" {{ old('account_type') == 'revenue' ? 'selected' : '' }}>إيرادات</option>
                        <option value="expense" {{ old('account_type') == 'expense' ? 'selected' : '' }}>مصاريف</option>
                    </select>
                    @error('account_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="parent_id" class="form-label fw-bold">الحساب الأب</label>
                    <select name="parent_id" id="parent_id" class="form-select select2 @error('parent_id') is-invalid @enderror">
                        <option value="">-- لا يوجد (حساب رئيسي) --</option>
                        @php
                            $suggestedParent = old('parent_id');
                            if ($entityType === \App\Models\Supplier::class && !$suggestedParent) $suggestedParent = \App\Models\ChartOfAccount::where('code', '2110')->value('id');
                            if ($entityType === \App\Models\Client::class && !$suggestedParent) $suggestedParent = \App\Models\ChartOfAccount::where('code', '1120')->value('id');
                        @endphp
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ ($suggestedParent ?? old('parent_id')) == $parent->id ? 'selected' : '' }}>{{ $parent->full_code }} - {{ $parent->name }}</option>
                            @foreach($parent->children as $child)
                                <option value="{{ $child->id }}" {{ ($suggestedParent ?? old('parent_id')) == $child->id ? 'selected' : '' }}>&nbsp;&nbsp;&nbsp;{{ $child->full_code }} - {{ $child->name }}</option>
                            @endforeach
                        @endforeach
                    </select>
                    @error('parent_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="shed_id" class="form-label fw-bold">مرتبط بعنبر (اختياري)</label>
                    <select name="shed_id" id="shed_id" class="form-select select2 @error('shed_id') is-invalid @enderror">
                        <option value="">-- غير مرتبط بعنبر --</option>
                        @foreach($sheds as $shed)
                            <option value="{{ $shed->id }}" {{ old('shed_id') == $shed->id ? 'selected' : '' }}>{{ $shed->name }}</option>
                        @endforeach
                    </select>
                    @error('shed_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="opening_balance" class="form-label fw-bold">الرصيد الافتتاحي</label>
                    <input type="number" name="opening_balance" id="opening_balance" class="form-control @error('opening_balance') is-invalid @enderror" value="{{ old('opening_balance', 0) }}" step="0.01" min="0">
                    @error('opening_balance')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="is_parent" class="form-label fw-bold">حساب رئيسي</label>
                    <select name="is_parent" id="is_parent" class="form-select">
                        <option value="0" {{ old('is_parent', 0) == '0' ? 'selected' : '' }}>لا - حساب فرعي</option>
                        <option value="1" {{ old('is_parent', 0) == '1' ? 'selected' : '' }}>نعم - حساب رئيسي (يمكن إضافة بنود فرعية)</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="is_active" class="form-label fw-bold">الحالة</label>
                    <select name="is_active" id="is_active" class="form-select">
                        <option value="1" {{ old('is_active', 1) == '1' ? 'selected' : '' }}>نشط</option>
                        <option value="0" {{ old('is_active', 1) == '0' ? 'selected' : '' }}>معطل</option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('chart-of-accounts.index') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-right me-1"></i>إلغاء
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save me-1"></i>حفظ الحساب
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
