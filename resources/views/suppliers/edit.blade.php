@extends('components.master')
@section('title','تعديل مورد')
@section('content')
<div class="row justify-content-center"><div class="col-md-7">
<div class="card">
    <div class="card-header"><h5 class="mb-0">تعديل مورد: {{ $supplier->name }}</h5></div>
    <div class="card-body">
        <form action="{{ route('suppliers.update',$supplier) }}" method="POST">
            @csrf @method('PATCH')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">اسم المورد <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ $supplier->name }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">الهاتف</label>
                    <input type="text" name="phone" class="form-control" value="{{ $supplier->phone }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">البريد الإلكتروني</label>
                    <input type="email" name="email" class="form-control" value="{{ $supplier->email }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">الرصيد الافتتاحي</label>
                    <input type="number" name="opening_balance" class="form-control" value="{{ $supplier->opening_balance }}" min="0" step="0.01">
                </div>
                <div class="col-12">
                    <label class="form-label">العنوان</label>
                    <input type="text" name="address" class="form-control" value="{{ $supplier->address }}">
                </div>
                <div class="col-12">
                    <label class="form-label">ملاحظات</label>
                    <textarea name="notes" class="form-control" rows="2">{{ $supplier->notes }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-warning flex-grow-1"><i class="fas fa-save me-1"></i>تحديث</button>
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary flex-grow-1">إلغاء</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
