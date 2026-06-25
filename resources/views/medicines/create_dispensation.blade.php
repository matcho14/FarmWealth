@extends('components.master')

@section('title', 'صرف دواء لدورة')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="page-header mb-4">
            <h1><i class="fas fa-hand-holding-medical me-2 text-primary"></i> صرف دواء بنظام البحث الذكي</h1>
            <p>يمكنك الآن البحث عن الأصناف والعنابر والدورات بسهولة.</p>
        </div>
        
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 text-dark">
                <form action="{{ route('medicines.dispense.store') }}" method="POST" id="dispenseForm">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="medicine_id" class="form-label fw-bold">1. اختر الدواء (ابحث بالاسم)</label>
                        <select class="form-select select2-search @error('medicine_id') is-invalid @enderror" id="medicine_id" name="medicine_id" required>
                            <option value="">-- ابحث عن دواء --</option>
                            @foreach($medicines as $med)
                            <option value="{{ $med->id }}">
                                {{ $med->name }} (الرصيد: {{ format_number($med->current_stock, 2) }} {{ $med->unit }})
                            </option>
                            @endforeach
                        </select>
                        @error('medicine_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="shed_id" class="form-label fw-bold">2. اختر العنبر</label>
                            <select class="form-select select2-search" id="shed_id" name="shed_id" required>
                                <option value="">-- ابحث عن عنبر --</option>
                                @foreach($sheds as $shed)
                                <option value="{{ $shed->id }}">{{ $shed->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="cycle_id" class="form-label fw-bold">3. اختر الدورة</label>
                            <select class="form-select select2-search @error('cycle_id') is-invalid @enderror" id="cycle_id" name="cycle_id" required disabled>
                                <option value="">-- اختر العنبر أولاً --</option>
                            </select>
                            @error('cycle_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="quantity" class="form-label fw-bold">الكمية المراد صرفها</label>
                            <div class="input-group input-group-lg">
                                <input type="number" step="0.01" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" required>
                                <span class="input-group-text bg-light" id="unit-label">وحدة</span>
                            </div>
                            @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="dispensation_date" class="form-label fw-bold">تاريخ الصرف</label>
                            <input type="date" class="form-control form-control-lg @error('dispensation_date') is-invalid @enderror" id="dispensation_date" name="dispensation_date" value="{{ date('Y-m-d') }}" required>
                            @error('dispensation_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg shadow-sm py-3">
                            <i class="fas fa-check-circle me-1"></i> حفظ وإتمام عملية الصرف
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        // الاعتماد على التهيئة العامة لـ Select2 في master.blade.php

        const sheds = @json($sheds);
        const shedSelect = $('#shed_id');
        const cycleSelect = $('#cycle_id');

        shedSelect.on('change', function() {
            const shedId = $(this).val();
            cycleSelect.empty().append('<option value="">-- ابحث عن دورة --</option>');
            
            if (shedId) {
                const selectedShed = sheds.find(s => s.id == shedId);
                if (selectedShed && selectedShed.cycles && selectedShed.cycles.length > 0) {
                    selectedShed.cycles.forEach(cycle => {
                        const startDate = cycle.start_date ? cycle.start_date.split('T')[0] : 'غير محدد';
                        const statusText = cycle.status === 'active' ? 'نشطة' : 'مكتملة';
                        
                        const option = new Option(`دورة #${cycle.id} - بداية: ${startDate} (${statusText})`, cycle.id, false, false);
                        cycleSelect.append(option);
                    });
                    cycleSelect.prop('disabled', false);
                } else {
                    cycleSelect.append('<option value="">لا توجد دورات لهذا العنبر</option>');
                    cycleSelect.prop('disabled', true);
                }
            } else {
                cycleSelect.append('<option value="">-- اختر العنبر أولاً --</option>');
                cycleSelect.prop('disabled', true);
            }
            
            // إعادة تهيئة Select2 للدورة بعد تحديث الخيارات
            cycleSelect.trigger('change');
        });

        // تحديث ملصق الوحدة عند اختيار الدواء (لمسة إضافية)
        $('#medicine_id').on('change', function() {
            const text = $(this).find('option:selected').text();
            if (text.includes('(')) {
                const unit = text.split(' ').pop().replace(')', '');
                $('#unit-label').text(unit);
            }
        });
    });
</script>
@endsection
@endsection
