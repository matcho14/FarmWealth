@extends('components.master')

@section('title', 'تعديل عنبر')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">تعديل عنبر: {{ $shed->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('sheds.update', $shed) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="name" class="form-label">اسم العنبر <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            class="form-control @error('name') is-invalid @enderror"
                            id="name"
                            name="name"
                            value="{{ $shed->name }}"
                            required>
                        @error('name')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">الوصف</label>
                        <textarea
                            class="form-control @error('description') is-invalid @enderror"
                            id="description"
                            name="description"
                            rows="3">{{ $shed->description }}</textarea>
                        @error('description')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="floors" class="form-label">عدد الأدوار <span class="text-danger">*</span></label>
                        <input
                            type="number"
                            class="form-control @error('floors') is-invalid @enderror"
                            id="floors"
                            name="floors"
                            min="1"
                            max="10"
                            value="{{ old('floors', $shed->floors) }}"
                            required>
                        <small class="text-muted">عدد الأدوار في هذا العنبر (1 إلى 10)</small>
                        @error('floors')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">الحالة <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="active" {{ $shed->status === 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ $shed->status === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        </select>
                        @error('status')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning flex-grow-1">
                            <i class="fas fa-save me-2"></i>تحديث
                        </button>
                        <a href="{{ route('sheds.show', $shed) }}" class="btn btn-secondary flex-grow-1">
                            <i class="fas fa-times me-2"></i>إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
