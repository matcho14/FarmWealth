@extends('components.master')
@section('title', 'وحدات القياس')

@section('content')
<div class="row mb-3">
    <div class="col-md-8">
        <h2><i class="fas fa-balance-scale me-2 text-primary"></i> إدارة وحدات القياس</h2>
    </div>
    <div class="col-md-4 text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUnitModal">
            <i class="fas fa-plus"></i> إضافة وحدة جديدة
        </button>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-hover table-striped mb-0 text-center">
            <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>اسم الوحدة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($units as $unit)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-bold">{{ $unit->name }}</td>
                        <td>
                            <!-- Edit Button -->
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editUnitModal{{ $unit->id }}">
                                <i class="fas fa-edit"></i> تعديل
                            </button>

                            <!-- Delete Form -->
                            <form action="{{ route('units.destroy', $unit->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('هل أنت متأكد من حذف هذه الوحدة؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i> حذف
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editUnitModal{{ $unit->id }}" tabindex="-1" aria-labelledby="editUnitModalLabel{{ $unit->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{ route('units.update', $unit->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="modal-content">
                                    <div class="modal-header bg-light">
                                        <h5 class="modal-title" id="editUnitModalLabel{{ $unit->id }}">تعديل وحدة القياس</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-start">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">اسم الوحدة <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" value="{{ $unit->name }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                        <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                @empty
                    <tr>
                        <td colspan="3" class="text-muted py-4">لا توجد وحدات قياس مضافة حتى الآن.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createUnitModal" tabindex="-1" aria-labelledby="createUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('units.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="createUnitModalLabel">إضافة وحدة قياس جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    <div class="mb-3">
                        <label for="name" class="form-label">اسم الوحدة <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required placeholder="مثال: كجم، طن، لتر...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">إضافة</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
