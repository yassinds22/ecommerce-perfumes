<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب جديد — Luxe Parfum</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #c5a059;
            --primary-dark: #a68545;
            --bg-dark: #0f1115;
            --card-bg: rgba(25, 28, 35, 0.7);
            --text-main: #e0e0e0;
            --text-muted: #a0a0a0;
            --glass-border: rgba(255, 255, 255, 0.05);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', 'Cairo', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            background-image: 
                radial-gradient(circle at 80% 20%, rgba(197, 160, 89, 0.05) 0%, transparent 40%),
                radial-gradient(circle at 20% 80%, rgba(197, 160, 89, 0.05) 0%, transparent 40%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-main);
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 500px;
            position: relative;
            z-index: 10;
        }

        .login-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            text-align: center;
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo-box {
            margin-bottom: 25px;
        }

        .logo-box i {
            font-size: 35px;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .logo-box h1 {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 2px;
            background: linear-gradient(135deg, #fff 0%, #c5a059 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .login-header {
            margin-bottom: 30px;
        }

        .login-header h2 {
            font-size: 22px;
            margin-bottom: 8px;
        }

        .login-header p {
            color: var(--text-muted);
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 18px;
            text-align: right;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            color: var(--text-muted);
            padding-right: 5px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 16px;
        }

        .input-wrapper input {
            width: 100%;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 12px 45px 12px 15px;
            color: #fff;
            font-size: 15px;
            transition: all 0.3s;
        }

        .input-wrapper input:focus {
            outline: none;
            border-color: var(--primary);
            background: rgba(197, 160, 89, 0.05);
        }

        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #000;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(197, 160, 89, 0.2);
        }

        .alert-error {
            background: rgba(220, 53, 69, 0.1);
            border-right: 4px solid #dc3545;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #ff6b6b;
            text-align: right;
        }

        .footer-links {
            margin-top: 25px;
            font-size: 14px;
            color: var(--text-muted);
        }

        .footer-links a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo-box">
                <i class="fas fa-crown"></i>
                <h1>LUXE PARFUM</h1>
            </div>

            <div class="login-header">
                <h2>انضم إلينا اليوم</h2>
                <p>قم بإنشاء حساب للاستمتاع بتجربة تسوق فريدة</p>
            </div>

            @if ($errors->any())
                <div class="alert-error">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>الاسم الكامل</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="أدخل اسمك الكامل">
                        <i class="fas fa-user"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label>البريد الإلكتروني</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="your@email.com">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label>كلمة المرور</label>
                    <div class="input-wrapper">
                        <input type="password" name="password" required placeholder="••••••••">
                        <i class="fas fa-lock"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label>تأكيد كلمة المرور</label>
                    <div class="input-wrapper">
                        <input type="password" name="password_confirmation" required placeholder="••••••••">
                        <i class="fas fa-check-double"></i>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <span>إنشاء الحساب</span>
                    <i class="fas fa-user-plus"></i>
                </button>
            </form>

            <p class="footer-links">
                لديك حساب بالفعل؟ <a href="{{ route('login') }}">تسجيل الدخول</a>
            </p>
        </div>
    </div>
</body>
</html>
