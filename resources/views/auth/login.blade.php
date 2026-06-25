<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - FarmWealth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f7f6; height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .login-card { width: 100%; max-width: 400px; border: none; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .card-header { background: #2c3e50; color: white; border-radius: 15px 15px 0 0 !important; text-align: center; padding: 2rem; }
        .btn-login { background: #2c3e50; color: white; border: none; padding: 10px; border-radius: 8px; width: 100%; font-weight: bold; transition: 0.3s; }
        .btn-login:hover { background: #1a252f; color: white; }
    </style>
</head>
<body>
    <div class="card login-card">
        <div class="card-header">
            <h3 class="mb-0"><i class="fas fa-tractor me-2"></i>FarmWealth</h3>
            <p class="mb-0 mt-2 small opacity-75">نظام إدارة مزارع الدواجن</p>
        </div>
        <div class="card-body p-4">
            <h5 class="text-center mb-4">تسجيل الدخول</h5>
            
            @if($errors->any())
                <div class="alert alert-danger py-2">
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">البريد الإلكتروني</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope text-muted"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="admin@example.com" required autofocus>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">كلمة السر</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="******" required>
                    </div>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">تذكرني على هذا الجهاز</label>
                </div>
                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>دخول
                </button>
            </form>
        </div>
    </div>
</body>
</html>
