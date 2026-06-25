@extends('components.master')
@section('title','تعديل العميل')
@section('content')
<div class="row justify-content-center"><div class="col-md-7">
<div class="card">
    <div class="card-header"><h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>تعديل العميل: {{ $client->name }}</h5></div>
    <div class="card-body">
        <form action="{{ route('clients.update', $client) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">اسم العميل <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $client->name) }}" required>
                    @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">الهاتف</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $client->phone) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">البريد الإلكتروني</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $client->email) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">الرصيد الافتتاحي</label>
                    <input type="number" name="opening_balance" class="form-control" value="{{ old('opening_balance', $client->opening_balance) }}" min="0" step="0.01">
                </div>
                <div class="col-12">
                    <label class="form-label">العنوان</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $client->address) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">ملاحظات</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $client->notes) }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-success flex-grow-1"><i class="fas fa-save me-1"></i>حفظ التغييرات</button>
                <a href="{{ route('clients.index') }}" class="btn btn-secondary flex-grow-1"><i class="fas fa-times me-1"></i>إلغاء</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection