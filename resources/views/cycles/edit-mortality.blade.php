@extends('components.master')

@section('title', 'إضافة نافق')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">إضافة نافق للدورة #{{ $cycle->id }}</h5>
                <small class="text-muted">عنبر: {{ $cycle->shed->name }} | عدد الأدوار: {{ $floors }}</small>
            </div>
            <div class="card-body">

                <div class="alert alert-info mb-3">
                    <strong>الحالة الحالية للدورة:</strong><br>
                    الكتاكيت الأولي: <strong>{{ $cycle->initial_chicks }}</strong> |
                    النافق الكلي: <strong>{{ $cycle->mortality_count }}</strong> |
                    المتبقي الكلي: <strong>{{ $cycle->expected_remaining }}</strong>
                </div>

                <form action="{{ route('cycles.updateMortality', $cycle) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="record_date" class="form-label">تاريخ التسجيل <span class="text-danger">*</span></label>
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

                    {{-- النافق حسب كل دور --}}
                    <div class="card mb-3 border-warning">
                        <div class="card-header bg-warning text-dark py-2">
                            <i class="fas fa-skull-crossbones me-2"></i>
                            عدد النافق في كل دور
                        </div>
                        <div class="card-body">
                            @if($errors->has('floor_mortality'))
                                <div class="alert alert-danger py-2">{{ $errors->first('floor_mortality') }}</div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead class="table-secondary">
                                        <tr class="text-center">
                                            <th>الدور</th>
                                            <th>الكتاكيت الأولي</th>
                                            <th>النافق المسجل</th>
                                            <th>المتبقي</th>
                                            <th>النافق الجديد</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for($floor = 1; $floor <= $floors; $floor++)
                                            @php
                                                $initialInFloor   = $floorChicks[$floor]  ?? 0;
                                                $deadInFloor      = $mortalityByFloor[$floor] ?? 0;
                                                $remainingInFloor = $remainingByFloor[$floor] ?? 0;
                                            @endphp
                                            <tr>
                                                <td class="fw-bold text-center">
                                                    <span class="badge bg-secondary fs-6">الدور {{ $floor }}</span>
                                                </td>
                                                <td class="text-center text-info fw-bold">{{ $initialInFloor }}</td>
                                                <td class="text-center text-danger fw-bold">{{ $deadInFloor }}</td>
                                                <td class="text-center text-success fw-bold">{{ $remainingInFloor }}</td>
                                                <td>
                                                    <input
                                                        type="number"
                                                        class="form-control form-control-sm text-center @error('floor_mortality.'.$floor) is-invalid @enderror"
                                                        name="floor_mortality[{{ $floor }}]"
                                                        min="0"
                                                        max="{{ $remainingInFloor }}"
                                                        value="{{ old('floor_mortality.'.$floor, 0) }}"
                                                        {{ $remainingInFloor <= 0 ? 'disabled' : '' }}>
                                                    @error('floor_mortality.'.$floor)
                                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                                    @enderror
                                                    @if($remainingInFloor <= 0)
                                                        <small class="text-muted d-block text-center">لا يوجد متبقي</small>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">ملاحظات</label>
                        <textarea
                            class="form-control"
                            id="notes"
                            name="notes"
                            rows="2"
                            placeholder="أي ملاحظات إضافية (اختياري)">{{ old('notes') }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning flex-grow-1">
                            <i class="fas fa-save me-2"></i>تسجيل النافق
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
