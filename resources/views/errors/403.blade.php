<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: #f8f9fa;
            font-family: 'Public Sans', sans-serif;
        }
        .error-box {
            text-align: center;
            padding: 60px 40px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            max-width: 480px;
        }
        .error-code {
            font-size: 80px;
            font-weight: 700;
            color: #dc3545;
            line-height: 1;
        }
        .error-title {
            font-size: 24px;
            font-weight: 600;
            margin: 16px 0 8px;
            color: #212529;
        }
        .error-desc {
            color: #6c757d;
            margin-bottom: 32px;
        }
        .btn-back {
            display: inline-block;
            padding: 10px 28px;
            background: #4680ff;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.2s;
        }
        .btn-back:hover {
            background: #2a5cd6;
            color: white;
        }
        .role-badge {
            display: inline-block;
            padding: 4px 12px;
            background: #e7f0ff;
            color: #4680ff;
            border-radius: 20px;
            font-size: 13px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="error-box">
        <div class="error-code">403</div>
        <div class="error-title">Akses Ditolak</div>
        @auth
        <div class="role-badge">Role kamu: {{ Auth::user()->role }}</div>
        @endauth
        <p class="error-desc">
            Kamu tidak memiliki izin untuk mengakses halaman ini.<br>
            Silakan hubungi admin jika ini adalah kesalahan.
        </p>
        <a href="{{ url('/home') }}" class="btn-back">← Kembali ke Dashboard</a>
    </div>
</body>
</html>