<!DOCTYPE html>
<html lang="id">
<head>
  <title>Login — Scomptec</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" href="{{ asset('assets/images/Scomptec.png') }}" type="image/png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap">
  <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
  <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}">
  <style>
    :root {
      --blue-dark:  #1a237e;
      --blue-mid:   #283593;
      --blue-light: #3949ab;
      --red:        #e53935;
      --red-dark:   #c62828;
      --cream:      #f5f6fa;
      --muted:      #8c9ec0;
      --border:     #dde3f0;
      --text-mid:   #5c6b8a;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: var(--blue-dark);
      min-height: 100vh;
      display: flex;
      align-items: stretch;
    }

    /* ══ LEFT ══ */
    .auth-left {
      flex: 0 0 42%;
      position: relative;
      display: flex; flex-direction: column;
      justify-content: flex-start; gap: 32px;
      padding: 40px 52px;
      background: linear-gradient(155deg, var(--blue-dark) 0%, var(--blue-mid) 60%, #1565c0 100%);
      overflow: hidden;
    }
    .auth-left::before {
      content: ''; position: absolute; top: 0; left: 0; right: 0;
      height: 4px; background: linear-gradient(90deg, var(--red), #ef5350);
    }
    .deco-circle-1 { position: absolute; width: 340px; height: 340px; border-radius: 50%; border: 1px solid rgba(255,255,255,0.06); top: -80px; right: -80px; }
    .deco-circle-2 { position: absolute; width: 200px; height: 200px; border-radius: 50%; border: 1px solid rgba(255,255,255,0.06); top: -10px; right: -10px; }
    .deco-circle-3 { position: absolute; width: 260px; height: 260px; border-radius: 50%; background: rgba(229,57,53,0.07); bottom: -50px; left: -70px; }
    .grid-overlay {
      position: absolute; inset: 0;
      background-image: linear-gradient(rgba(255,255,255,0.025) 1px,transparent 1px), linear-gradient(90deg,rgba(255,255,255,0.025) 1px,transparent 1px);
      background-size: 44px 44px;
    }
    .brand { position: relative; z-index: 2; }
    .brand-logo { height: 130px; object-fit: contain; margin-top: -10px; display: block; filter: brightness(0) invert(1); }
    .left-content { position: relative; z-index: 2; }
    .left-tagline { font-size: 1.85rem; font-weight: 800; line-height: 1.25; color: #fff; margin-bottom: 12px; }
    .left-tagline .accent { color: var(--red); display: block; }
    .left-sub { font-size: 0.87rem; color: rgba(255,255,255,0.55); line-height: 1.75; border-left: 3px solid var(--red); padding-left: 12px; max-width: 300px; }
    .feature-list { position: relative; z-index: 2; display: flex; flex-direction: column; gap: 12px; }
    .feature-item { display: flex; align-items: center; gap: 12px; }
    .feature-icon { flex-shrink: 0; width: 36px; height: 36px; border-radius: 8px; background: rgba(229,57,53,0.12); border: 1px solid rgba(229,57,53,0.28); display: flex; align-items: center; justify-content: center; font-size: 16px; }
    .feature-text { font-size: 0.85rem; color: rgba(255,255,255,0.7); font-weight: 500; }

    /* ══ RIGHT ══ */
    .auth-right {
      flex: 1; display: flex; align-items: center; justify-content: center;
      padding: 48px 64px; background: var(--cream);
      animation: fadeSlide 0.5s cubic-bezier(0.22,1,0.36,1) both;
    }
    @keyframes fadeSlide { from { opacity:0; transform:translateX(24px); } to { opacity:1; transform:translateX(0); } }
    .form-box { width: 100%; max-width: 400px; }
    .form-top-bar { height: 4px; background: linear-gradient(90deg, var(--red), #ef5350); border-radius: 4px 4px 0 0; }
    .form-card {
      background: #fff; border-radius: 0 0 16px 16px;
      padding: 32px 36px 36px;
      box-shadow: 0 4px 32px rgba(26,35,126,0.08);
      border: 1px solid var(--border); border-top: none;
      margin-bottom: 16px;
    }
    .form-header { margin-bottom: 24px; }
    .form-header h2 { font-size: 1.5rem; font-weight: 800; color: var(--blue-dark); margin-bottom: 6px; }
    .form-header p { font-size: 0.87rem; color: var(--text-mid); }
    .form-header p a { color: var(--red); font-weight: 600; text-decoration: none; }
    .form-header p a:hover { color: var(--red-dark); }
    .alert-success { background: #f0fff4; border: 1px solid #9ae6b4; border-left: 3px solid #38a169; border-radius: 10px; padding: 12px 14px; margin-bottom: 18px; font-size: 0.85rem; color: #276749; }
    .alert-error { background: #fff5f5; border: 1px solid #feb2b2; border-left: 3px solid var(--red); border-radius: 10px; padding: 12px 14px; margin-bottom: 18px; font-size: 0.85rem; color: #c53030; }
    .field-group { margin-bottom: 16px; }
    .field-group label { display: block; font-size: 0.74rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: var(--blue-dark); margin-bottom: 7px; }
    .input-wrap { position: relative; }
    .input-wrap .input-icon { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 15px; pointer-events: none; }
    .field-group input { width: 100%; padding: 11px 14px 11px 40px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.9rem; color: var(--blue-dark); background: var(--cream); border: 1.5px solid var(--border); border-radius: 10px; outline: none; transition: border-color 0.2s, box-shadow 0.2s, background 0.2s; }
    .field-group input::placeholder { color: #b0bcc8; }
    .field-group input:focus { background: #fff; border-color: var(--blue-light); box-shadow: 0 0 0 3px rgba(57,73,171,0.12); }
    .remember-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; }
    .check-label { display: flex; align-items: center; gap: 8px; font-size: 0.84rem; color: var(--text-mid); cursor: pointer; }
    .check-label input[type="checkbox"] { width: 15px; height: 15px; accent-color: var(--blue-dark); }
    .forgot-link { font-size: 0.84rem; color: var(--red); font-weight: 600; text-decoration: none; }
    .forgot-link:hover { color: var(--red-dark); }
    .btn-submit { width: 100%; padding: 13px; background: linear-gradient(135deg, var(--blue-dark) 0%, var(--blue-light) 100%); color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.92rem; font-weight: 700; letter-spacing: 0.03em; border: none; border-radius: 10px; cursor: pointer; transition: all 0.25s; position: relative; overflow: hidden; }
    .btn-submit::after { content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent); transition: left 0.5s; }
    .btn-submit:hover::after { left: 100%; }
    .btn-submit:hover { box-shadow: 0 6px 20px rgba(26,35,126,0.3); transform: translateY(-1px); }
    .btn-submit:active { transform: translateY(0); }
    .divider { display: flex; align-items: center; gap: 12px; margin: 20px 0; }
    .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }
    .divider span { font-size: 0.74rem; color: #9aabb8; text-transform: uppercase; letter-spacing: 0.08em; white-space: nowrap; }
    .social-row { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; }
    .btn-social { display: flex; align-items: center; justify-content: center; gap: 6px; padding: 10px 8px; background: var(--cream); border: 1.5px solid var(--border); border-radius: 10px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.8rem; font-weight: 500; color: #4a5568; cursor: pointer; transition: all 0.2s; }
    .btn-social img { width: 16px; height: 16px; object-fit: contain; }
    .btn-social:hover { background: #fff; border-color: var(--blue-light); box-shadow: 0 2px 10px rgba(26,35,126,0.1); }
    .form-footer { display: flex; justify-content: space-between; align-items: center; }
    .form-footer p { font-size: 0.74rem; color: #9aabb8; }
    .form-footer a { font-size: 0.74rem; color: #9aabb8; text-decoration: none; }
    .form-footer a:hover { color: var(--blue-mid); }
    .loader-bg { display: none; }
    @media (max-width: 900px) { .auth-left { display: none; } .auth-right { padding: 32px 20px; } }
  </style>
</head>
<body>
  <div class="loader-bg"><div class="loader-track"><div class="loader-fill"></div></div></div>

  <div class="auth-left">
    <div class="grid-overlay"></div>
    <div class="deco-circle-1"></div><div class="deco-circle-2"></div><div class="deco-circle-3"></div>

    <div class="brand">
      <img src="{{ asset('assets/images/Scomptec.png') }}" alt="Scomptec" class="brand-logo">
    </div>

    <div class="left-content">
      <div class="left-tagline">
        Belajar lebih mudah,<br>daftar kelas
        <span class="accent">kapan saja.</span>
      </div>
      <p class="left-sub">
        Platform pembelian kelas Scomptec — temukan kelas yang kamu butuhkan dan mulai belajar hari ini.
      </p>
    </div>

    <div class="feature-list">
      <div class="feature-item">
        <div class="feature-icon">🎓</div>
        <div class="feature-text">Pilihan kelas lengkap & terstruktur</div>
      </div>
      <div class="feature-item">
        <div class="feature-icon">💳</div>
        <div class="feature-text">Pembelian kelas mudah & cepat</div>
      </div>
      <div class="feature-item">
        <div class="feature-icon">📋</div>
        <div class="feature-text">Pantau riwayat transaksi kapan saja</div>
      </div>
    </div>
  </div>

  <div class="auth-right">
    <div class="form-box">
      <div class="form-top-bar"></div>
      <div class="form-card">

        <div class="form-header">
          <h2>Masuk ke akun kamu 👋</h2>
          <p>Belum punya akun? <a href="{{ url('/home/register') }}">Daftar di sini</a></p>
        </div>

        @if(session('success'))
          <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
          <div class="alert-error">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
          </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
          @csrf
          <div class="field-group">
            <label>Alamat Email</label>
            <div class="input-wrap">
              <span class="input-icon"><i class="feather icon-mail"></i></span>
              <input type="email" name="email" placeholder="kamu@scomptec.com"
                     value="{{ old('email') }}" required autocomplete="email">
            </div>
          </div>
          <div class="field-group">
            <label>Password</label>
            <div class="input-wrap">
              <span class="input-icon"><i class="feather icon-lock"></i></span>
              <input type="password" name="password" placeholder="••••••••"
                     required autocomplete="current-password">
            </div>
          </div>
          <div class="remember-row">
            <label class="check-label">
              <input type="checkbox" name="remember"> Ingat saya
            </label>
            <a href="#" class="forgot-link">Lupa password?</a>
          </div>
          <button type="submit" class="btn-submit">Masuk ke Portal</button>
        </form>

        <div class="divider"><span>atau lanjutkan dengan</span></div>
        <div class="social-row">
          <button type="button" class="btn-social">
            <img src="{{ asset('assets/images/authentication/google.svg') }}" alt="Google"> Google
          </button>
          <button type="button" class="btn-social">
            <img src="{{ asset('assets/images/authentication/twitter.svg') }}" alt="Twitter"> Twitter
          </button>
          <button type="button" class="btn-social">
            <img src="{{ asset('assets/images/authentication/facebook.svg') }}" alt="Facebook"> Facebook
          </button>
        </div>

      </div>
      <div class="form-footer">
        <p>© {{ date('Y') }} Scomptec. All rights reserved.</p>
        <div style="display:flex; gap:14px;">
          <a href="#">Privasi</a>
          <a href="#">Ketentuan</a>
          <a href="#">Kontak</a>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>
  <script src="{{ asset('assets/js/pcoded.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
  <script>layout_change('light');</script>
  <script>change_box_container('false');</script>
  <script>layout_rtl_change('false');</script>
  <script>preset_change("preset-1");</script>
  <script>font_change("Public-Sans");</script>
</body>
</html>