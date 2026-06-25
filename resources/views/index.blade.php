@extends('components.master')

@section('title', 'الصفحة الرئيسية')

@section('content')
<div class="page-header">
    <h1><i class="fa-solid fa-gauge-high"></i> الصفحة الرئيسية</h1>
    <p>مرحباً بك في نظام إدارة مزارع الدواجن - لوحة التحكم الرئيسية</p>
</div>

@if(auth()->check())
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="fas fa-house-farm me-2"></i>العنابر</h2>
        </div>
    </div>

    @if(auth()->user()->hasPermission('sheds'))
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">إدارة العنابر</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p>إدارة وعرض جميع العنابر في المزرعة</p>
                                <a href="{{ route('sheds.index') }}" class="btn btn-primary">
                                    <i class="fas fa-list me-2"></i>عرض العنابر
                                </a>
                            </div>
                            <div class="col-md-6 text-end">
                                <i class="fas fa-house" style="font-size: 4rem; color: #3498db; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(auth()->user()->hasPermission('cycles'))
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">الدورات</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p>إدارة دورات تربية الدواجن ومتابعة الكتاكيت والنافق</p>
                                <a href="{{ route('cycles.index') }}" class="btn btn-success">
                                    <i class="fas fa-sync-alt me-2"></i>عرض الدورات
                                </a>
                            </div>
                            <div class="col-md-6 text-end">
                                <i class="fas fa-clock-rotate-left" style="font-size: 4rem; color: #27ae60; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">المالية</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p>إدارة المعاملات المالية للمزرعة</p>
                            <div class="d-flex flex-wrap gap-2">
                                @if(auth()->user()->hasPermission('suppliers'))
                                    <a href="{{ route('suppliers.index') }}" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-truck me-1"></i>الموردين
                                    </a>
                                @endif
                                @if(auth()->user()->hasPermission('clients'))
                                    <a href="{{ route('clients.index') }}" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-users me-1"></i>العملاء
                                    </a>
                                @endif
                                @if(auth()->user()->hasPermission('treasuries'))
                                    <a href="{{ route('treasuries.index') }}" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-vault me-1"></i>الخزائن
                                    </a>
                                @endif
                                @if(auth()->user()->hasPermission('journal-entries'))
                                    <a href="{{ route('journal-entries.index') }}" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-book me-1"></i>القيود اليومية
                                    </a>
                                @endif
                                @if(auth()->user()->hasPermission('purchase-invoices'))
                                    <a href="{{ route('purchase-invoices.index') }}" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-file-invoice me-1"></i>فواتير الشراء
                                    </a>
                                @endif
                                @if(auth()->user()->hasPermission('sale-invoices'))
                                    <a href="{{ route('sale-invoices.index') }}" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-receipt me-1"></i>فواتير البيع
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <i class="fas fa-coins" style="font-size: 4rem; color: #f39c12; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->isAdmin())
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">إدارة المستخدمين</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p>إدارة حسابات المستخدمين والصلاحيات</p>
                                <a href="{{ route('users.index') }}" class="btn btn-dark">
                                    <i class="fas fa-users-cog me-2"></i>إدارة المستخدمين
                                </a>
                            </div>
                            <div class="col-md-6 text-end">
                                <i class="fas fa-users" style="font-size: 4rem; color: #6c757d; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@else
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                يرجى <a href="{{ route('login') }}">تسجيل الدخول</a> للوصول إلى لوحة التحكم
            </div>
        </div>
    </div>
@endif
@endsection