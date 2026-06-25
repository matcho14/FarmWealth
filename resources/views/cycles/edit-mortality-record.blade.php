@extends('components.master')

@section('title', 'تعديل سجل نافق')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">تعديل سجل النافق - الدورة #{{ $cycle->id }}</h5>
                <small class="text-muted">عنبر: {{ $cycle->shed->name }}</small>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-3">
                    <strong>بيانات الدورة:</strong><br>
                    الكتاكيت الأولي: <strong>{{ $cycle->initial_chicks }}</strong> |
                    النافق المسجل قبل التعديل: <strong>{{ $cycle->mortality_count }}</strong>
                </div>

                <form action="{{ route('cycles.updateMortalityRecord', [$cycle, $mortalityRecord]) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="record_date" class="form-label">تاريخ التسجيل <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('record_date') is-invalid @enderror" id="record_date" name="record_date" value="{{ old('record_date', $mortalityRecord->record_date->toDateString()) }}" required>
                        @error('record_date')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="floor_number" class="form-label">الدور <span class="text-danger">*</span></label>
                        <select class="form-select @error('floor_number') is-invalid @enderror" id="floor_number" name="floor_number" required>
                            <option value="">-- اختر الدور --</option>
                            @for($floor = 1; $floor <= $floors; $floor++)
                                <option value="{{ $floor }}" {{ old('floor_number', $mortalityRecord->floor_number) == $floor ? 'selected' : '' }}>الدور {{ $floor }}</option>
                            @endfor
                        </select>
                        @error('floor_number')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="count" class="form-label">عدد النافق <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('count') is-invalid @enderror" id="count" name="count" value="{{ old('count', $mortalityRecord->count) }}" min="0" required>
                        <small class="text-muted d-block">لا يمكن أن يتجاوز إجمالي النافق في الدور المختار الكتاكيت الأولي للدور.</small>
                        @error('count')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">ملاحظات</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $mortalityRecord->notes) }}</textarea>
                        @error('notes')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning flex-grow-1">
                            <i class="fas fa-save me-2"></i>تحديث سجل النافق
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
