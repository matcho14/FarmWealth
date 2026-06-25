@extends('components.master')
@section('title', 'إدارة المستخدمين')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-users-cog me-2 text-primary"></i>إدارة المستخدمين والصلاحيات</h3>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="fas fa-user-plus me-1"></i>إضافة مستخدم جديد
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>النوع (الدور)</th>
                        <th>الصلاحيات</th>
                        <th>تاريخ الإضافة</th>
                        <th>العمليات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="fw-bold">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'info' }}">
                                {{ $user->role === 'admin' ? 'مدير نظام (Admin)' : 'مستخدم عادي' }}
                            </span>
                        </td>
                        <td class="text-start">
                            @if($user->role === 'admin')
                                <span class="badge bg-dark">كل الصلاحيات</span>
                            @else
                                @php $userPerms = $user->permissions ?? []; @endphp
                                @foreach(App\Http\Controllers\UserController::$availablePermissions as $key => $label)
                                    @if(in_array($key, $userPerms))
                                        <span class="badge bg-light text-dark border mb-1">{{ $label }}</span>
                                    @endif
                                @endforeach
                                @if(empty($userPerms))
                                    <span class="text-muted small">لا توجد صلاحيات</span>
                                @endif
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
