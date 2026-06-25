@extends('components.master')
@section('title', 'تعديل بيانات المستخدم')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>تعديل مستخدم: {{ $user->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf @method('PUT')
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">الاسم الكامل <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">البريد الإلكتروني <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">كلمة السر (اتركها فارغة إذا لم ترد التغيير)</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">تأكيد كلمة السر</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">نوع الحساب (الدور) <span class="text-danger">*</span></label>
                            <select name="role" id="roleSelect" class="form-select" required onchange="togglePermissions()">
                                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>مستخدم عادي (صلاحيات محددة)</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>مدير نظام (Admin - تحكم كامل)</option>
                            </select>
                        </div>
                    </div>

                    <div id="permissionsSection" class="mt-4 col-md-12" style="{{ old('role', $user->role) == 'admin' ? 'display:none' : '' }}">
                        <h6 class="fw-bold border-bottom pb-2 mb-3">تحديد الصلاحيات (القوائم المسموح بها)</h6>
                        <div id="shedAssignment" class="col-md-12 mb-3" style="{{ (old('permissions') && in_array('sheds', old('permissions'))) || (in_array('sheds', $user->permissions ?? [])) ? '' : 'display:none' }}">
                            <label class="form-label fw-bold">ربط بعنبر محدد (اختياري)</label>
                            <select name="assigned_shed_id" class="form-select">
                                <option value="">-- جميع العنابر --</option>
                                @foreach($sheds as $shed)
                                    <option value="{{ $shed->id }}" {{ old('assigned_shed_id', $user->assigned_shed_id ?? '') == $shed->id ? 'selected' : '' }}>
                                        {{ $shed->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">إذا اخترت عنبراً، فلن يرى المستخدم سوى هذا العنبر فقط.</small>
                        </div>
                        <div class="row g-3">
                            @php $userPerms = old('permissions', $user->permissions ?? []); @endphp
                            @foreach($permissions as $key => $label)
                            <div class="col-md-4">
                                <div class="form-check card p-2 h-100 hover-shadow transition-all">
                                    <input class="form-check-input ms-2" type="checkbox" name="permissions[]" value="{{ $key }}" id="perm_{{ $key }}" {{ in_array($key, $userPerms) ? 'checked' : '' }}>
                                    <label class="form-check-label flex-grow-1" for="perm_{{ $key }}">
                                        {{ $label }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-warning btn-lg flex-grow-1">
                            <i class="fas fa-save me-2"></i>تحديث البيانات
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-lg flex-grow-1">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePermissions() {
    const role = document.getElementById('roleSelect').value;
    const section = document.getElementById('permissionsSection');
    section.style.display = role === 'admin' ? 'none' : 'block';
    toggleShedAssignment();
}

function toggleShedAssignment() {
    const checked = document.querySelectorAll('input[name="permissions[]"]:checked');
    const shedPerm = Array.from(checked).some(cb => cb.value === 'sheds');
    const div = document.getElementById('shedAssignment');
    if (div) {
        div.style.display = shedPerm ? 'block' : 'none';
    }
}

document.addEventListener('DOMContentLoaded', toggleShedAssignment);
document.querySelectorAll('input[name="permissions[]"]').forEach(cb => {
    cb.addEventListener('change', toggleShedAssignment);
});
</script>
<style>
    .hover-shadow:hover { box-shadow: 0 4px 8px rgba(0,0,0,0.1); cursor: pointer; }
    .transition-all { transition: all 0.2s ease; }
</style>
@endsection
