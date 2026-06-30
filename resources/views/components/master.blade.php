<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($title) ? $title . ' - ' : '' }}نظام إدارة مزارع الدواجن</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <style>
        :root {
            --nav-start: #327fb8;
            --nav-end: #3e98d6;
            --nav-deep: #31597a;
            --nav-active: rgba(255, 255, 255, 0.18);
            --page-bg: #f4f7fb;
            --card-border: #e4eaf2;
            --text-dark: #18324d;
        }

        html, body {
            min-height: 100%;
        }

        body {
            background: var(--page-bg);
            color: var(--text-dark);
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .app-navbar {
            background: linear-gradient(90deg, var(--nav-deep) 0%, var(--nav-start) 52%, var(--nav-end) 100%);
            box-shadow: 0 2px 10px rgba(12, 41, 66, 0.16);
            padding: 0.9rem 1.25rem;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar-brand {
            color: #fff !important;
            font-size: 1.55rem;
            font-weight: 700;
            letter-spacing: 0.2px;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            white-space: nowrap;
        }

        .brand-mark {
            width: 2.45rem;
            height: 2.45rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.95);
            color: var(--nav-start);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.25);
            flex: 0 0 auto;
        }

        .navbar-toggler {
            border: 0;
            box-shadow: none !important;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .navbar .nav-link {
            color: rgba(255, 255, 255, 0.92) !important;
            font-weight: 700;
            border-radius: 0.3rem;
            padding: 0.65rem 0.9rem !important;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            line-height: 1;
            white-space: nowrap;
        }

        .navbar .nav-link:hover,
        .navbar .nav-link.active,
        .navbar .show > .nav-link.dropdown-toggle {
            background: var(--nav-active);
            color: #fff !important;
        }

        .navbar .nav-link i {
            font-size: 0.95rem;
        }

        .navbar-nav {
            gap: 0.35rem;
            align-items: center;
        }

        .dropdown-menu {
            border: 0;
            border-radius: 0.95rem;
            box-shadow: 0 18px 45px rgba(26, 54, 89, 0.18);
            padding: 0.45rem 0;
            min-width: 19rem;
            overflow: hidden;
            z-index: 1040;
            background-color: #fff;
        }

        .dropdown-header {
            padding: 0.65rem 1rem 0.45rem;
            margin: 0;
            font-size: 0.88rem;
            font-weight: 700;
            color: #7b8da2;
            background: #f7f9fc;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.75rem 1rem;
            font-weight: 600;
            color: #23415f;
        }

        .dropdown-item i {
            width: 1.25rem;
            text-align: center;
            color: #29455f;
        }

        .dropdown-item:hover,
        .dropdown-item.active {
            background: #edf5fb;
            color: #0f5686;
        }

        .dropdown-divider {
            margin: 0.35rem 0;
        }

        .main-content {
            flex: 1 0 auto;
            padding: 1.6rem 1rem 2rem;
        }

        .page-header {
            background: #fff;
            border-radius: 1rem;
            padding: 1.5rem 1.6rem;
            margin-bottom: 1.3rem;
            border: 1px solid var(--card-border);
            box-shadow: 0 1px 3px rgba(20, 40, 80, 0.05);
        }

        .page-header h1 {
            margin: 0;
            color: var(--text-dark);
            font-size: 2rem;
            font-weight: 800;
        }

        .page-header p {
            margin: 0.45rem 0 0;
            color: #66788d;
        }

        .alert {
            border: 0;
            border-right: 4px solid transparent;
            border-radius: 0.8rem;
            box-shadow: 0 8px 20px rgba(16, 28, 46, 0.06);
        }

        .alert-success { border-right-color: #2ecc71; }
        .alert-danger { border-right-color: #e74c3c; }
        .alert-warning { border-right-color: #f39c12; }
        .alert-info { border-right-color: #3498db; }

        .footer {
            flex-shrink: 0;
            background: #fff;
            border-top: 1px solid var(--card-border);
            color: #5c6f84;
            padding: 1rem 1.5rem;
            text-align: center;
        }

        .btn {
            border-radius: 0.55rem;
            font-weight: 600;
        }

        .financial-menu .dropdown-menu {
            min-width: 20rem;
        }

        .user-menu .nav-link {
            background: rgba(255, 255, 255, 0.06);
        }

        @media (max-width: 991.98px) {
            .app-navbar {
                padding: 0.8rem 0.9rem;
            }

            .navbar-nav {
                align-items: stretch;
                gap: 0.25rem;
                margin-top: 0.9rem;
                width: 100%;
            }

            .navbar .nav-link {
                width: 100%;
                justify-content: space-between;
            }

            .dropdown-menu {
                min-width: 100%;
            }

            .main-content {
                padding-inline: 0.75rem;
            }
        }
    </style>

    @yield('styles')
</head>
<body>
    @php
        $user = auth()->user();
        $financialMenuActive = request()->routeIs(
            'suppliers.*',
            'clients.*',
            'items.*',
            'units.*',
            'purchase-invoices.*',
            'sale-invoices.*',
            'inventory.*',
            'treasuries.*',
            'journal-entries.*',
            'financial-records.*',
            'chart-of-accounts.*',
            'annual-report',
            'expense-report'
        );
    @endphp

    <nav class="navbar navbar-expand-lg navbar-dark app-navbar">
        <div class="container-fluid px-0 px-lg-2">
            <a class="navbar-brand order-2 order-lg-1 ms-lg-0 me-lg-3" href="{{ route('dashboard') }}">
                <span class="brand-mark"><i class="fa-solid fa-egg"></i></span>
                <span>إدارة الدواجن</span>
            </a>

            <button class="navbar-toggler order-1 order-lg-2" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse order-3 order-lg-2" id="mainNavbar">
                <ul class="navbar-nav me-auto ms-lg-0 flex-wrap">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fa-solid fa-gauge-high"></i>
                            <span>الصفحة الرئيسية</span>
                        </a>
                    </li>

                    @if($user && $user->hasPermission('sheds'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('sheds.*') ? 'active' : '' }}" href="{{ route('sheds.index') }}">
                                <i class="fa-solid fa-house"></i>
                                <span>العنابر</span>
                            </a>
                        </li>
                    @endif

                    @if($user && $user->hasPermission('cycles'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('cycles.*') ? 'active' : '' }}" href="{{ route('cycles.index') }}">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                                <span>الدورات</span>
                            </a>
                        </li>
                    @endif

                    <li class="nav-item dropdown financial-menu">
                        <a class="nav-link dropdown-toggle {{ $financialMenuActive ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-coins"></i>
                            <span>المالية</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><div class="dropdown-header">الموردين والمخزون</div></li>
                            @if($user && $user->hasPermission('suppliers'))
                                <li><a class="dropdown-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}"><i class="fa-solid fa-truck"></i> الموردين</a></li>
                            @endif
                            @if($user && $user->hasPermission('clients'))
                                <li><a class="dropdown-item {{ request()->routeIs('clients.*') ? 'active' : '' }}" href="{{ route('clients.index') }}"><i class="fa-solid fa-users"></i> العملاء</a></li>
                            @endif
                            @if($user && $user->hasPermission('items'))
                                <li><a class="dropdown-item {{ request()->routeIs('items.*') ? 'active' : '' }}" href="{{ route('items.index') }}"><i class="fa-solid fa-boxes-stacked"></i> الأصناف (المخزن الرئيسي)</a></li>
                            @endif
                            @if($user && $user->hasPermission('inventory'))
                                <li><a class="dropdown-item {{ request()->routeIs('inventory.index') ? 'active' : '' }}" href="{{ route('inventory.index') }}"><i class="fa-solid fa-warehouse"></i> مخازن العنابر</a></li>
                                <li><a class="dropdown-item {{ request()->routeIs('inventory.transfer.*') ? 'active' : '' }}" href="{{ route('inventory.transfer.create') }}"><i class="fa-solid fa-right-left"></i> تحويل عهدة للعنبر</a></li>
                            @endif
                            @if($user && $user->hasPermission('purchase-invoices'))
                                <li><a class="dropdown-item {{ request()->routeIs('purchase-invoices.*') ? 'active' : '' }}" href="{{ route('purchase-invoices.index') }}"><i class="fa-solid fa-file-invoice"></i> فواتير الشراء</a></li>
                            @endif
                            @if($user && $user->hasPermission('sale-invoices'))
                                <li><a class="dropdown-item {{ request()->routeIs('sale-invoices.*') ? 'active' : '' }}" href="{{ route('sale-invoices.index') }}"><i class="fa-solid fa-receipt"></i> فواتير البيع</a></li>
                            @endif

                            <li><hr class="dropdown-divider"></li>
                            <li><div class="dropdown-header">المحاسبة</div></li>
                            @if($user && $user->hasPermission('chart-of-accounts'))
                                <li><a class="dropdown-item {{ request()->routeIs('chart-of-accounts.*') ? 'active' : '' }}" href="{{ route('chart-of-accounts.index') }}"><i class="fa-solid fa-sitemap"></i> شجرة الحسابات</a></li>
                            @endif
                            @if($user && $user->hasPermission('annual-report'))
                                <li><a class="dropdown-item {{ request()->routeIs('annual-report') ? 'active' : '' }}" href="{{ route('annual-report') }}"><i class="fa-solid fa-file-pdf"></i> التقرير السنوي</a></li>
                            @endif
                            @if($user && $user->hasPermission('annual-report'))
                                <li><a class="dropdown-item {{ request()->routeIs('expense-report') ? 'active' : '' }}" href="{{ route('expense-report') }}"><i class="fa-solid fa-magnifying-glass-chart"></i> تقرير المصاريف</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li><div class="dropdown-header">الخزائن والقيود</div></li>
                            @if($user && $user->hasPermission('treasuries'))
                                <li><a class="dropdown-item {{ request()->routeIs('treasuries.*') ? 'active' : '' }}" href="{{ route('treasuries.index') }}"><i class="fa-solid fa-vault"></i> الخزائن</a></li>
                            @endif
                            @if($user && $user->hasPermission('journal-entries'))
                                <li><a class="dropdown-item {{ request()->routeIs('journal-entries.*') ? 'active' : '' }}" href="{{ route('journal-entries.index') }}"><i class="fa-solid fa-book"></i> القيود اليومية</a></li>
                            @endif
                        </ul>
                    </li>

                    @if($user && $user->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                <i class="fa-solid fa-users"></i>
                                <span>إدارة المستخدمين</span>
                            </a>
                        </li>
                    @endif

                    <li class="nav-item dropdown user-menu" data-bs-auto-close="false">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-user"></i>
                            <span>{{ $user?->name ?? 'الحساب' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if($user)
                                <li><div class="dropdown-header">{{ $user->name }}</div></li>
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fa-solid fa-gauge-high"></i> لوحة التحكم</a></li>
<li><hr class="dropdown-divider"></li>
                             <li><div class="dropdown-header">الإعدادات</div></li>
                             @if($user && $user->hasPermission('units'))
                                 <li><a class="dropdown-item {{ request()->routeIs('units.*') ? 'active' : '' }}" href="{{ route('units.index') }}"><i class="fa-solid fa-balance-scale"></i> وحدات القياس</a></li>
                             @endif
                             <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item w-100 border-0 bg-transparent text-start text-danger">
                                            <i class="fa-solid fa-right-from-bracket"></i> تسجيل الخروج
                                        </button>
                                    </form>
                                </li>
                            @else
                                <li><a class="dropdown-item" href="{{ route('login') }}"><i class="fa-solid fa-right-to-bracket"></i> تسجيل الدخول</a></li>
                            @endif
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="container-fluid">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-1"></i>
                    <strong>حدث خطأ!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-check me-1"></i>
                    <strong>تم بنجاح!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-1"></i>
                    <strong>خطأ!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i>
                    <strong>تنبيه!</strong> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="footer">
        <p class="mb-0">&copy; {{ date('Y') }} نظام إدارة مزارع الدواجن | جميع الحقوق محفوظة</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    bsAlert.close();
                }, 5000);
            });
        });

        $(function () {
            $('select').each(function () {
                if (!$(this).hasClass('select2-hidden-accessible')) {
                    const modal = $(this).closest('.modal');
                    const config = {
                        theme: 'bootstrap-5',
                        dir: 'rtl',
                        width: '100%'
                    };

                    if (modal.length) {
                        config.dropdownParent = modal;
                    }

                    $(this).select2(config);
                }
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
