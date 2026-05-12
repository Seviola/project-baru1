@extends('layouts.app')

@section('content')
<style>
  :root {
    --blue-dark:  #1a237e;
    --blue-light: #3949ab;
    --red:        #e53935;
    --border:     #dde3f0;
    --cream:      #f5f6fa;
    --text-mid:   #5c6b8a;
  }

  .profile-wrapper {
    max-width: 780px;
    margin: 32px auto;
    padding: 0 16px;
  }

  .profile-header-card {
    background: linear-gradient(135deg, var(--blue-dark) 0%, #3949ab 100%);
    border-radius: 16px;
    padding: 32px 36px;
    display: flex;
    align-items: center;
    gap: 28px;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
  }

  .profile-header-card::before {
    content: '';
    position: absolute; inset: 0;
    background-image: linear-gradient(rgba(255,255,255,0.03) 1px,transparent 1px),
                      linear-gradient(90deg,rgba(255,255,255,0.03) 1px,transparent 1px);
    background-size: 32px 32px;
  }

  /* Avatar besar di header */
  .avatar-lg {
    position: relative; flex-shrink: 0; z-index: 2;
  }

  .avatar-circle-lg {
    width: 88px; height: 88px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 2rem; font-weight: 800; color: #fff;
    border: 3px solid rgba(255,255,255,0.3);
    font-family: 'Plus Jakarta Sans', sans-serif;
    overflow: hidden;
  }

  .avatar-circle-lg img {
    width: 100%; height: 100%; object-fit: cover;
  }

  .avatar-upload-btn {
    position: absolute; bottom: 0; right: 0;
    width: 28px; height: 28px; border-radius: 50%;
    background: var(--red); border: 2px solid #fff;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: transform 0.2s;
  }

  .avatar-upload-btn:hover { transform: scale(1.1); }
  .avatar-upload-btn i { font-size: 13px; color: #fff; }

  .profile-header-info { z-index: 2; }

  .profile-header-info h4 {
    font-size: 1.3rem; font-weight: 800; color: #fff; margin-bottom: 4px;
    font-family: 'Plus Jakarta Sans', sans-serif;
  }

  .profile-header-info .role-badge {
    display: inline-block; padding: 3px 12px;
    background: rgba(229,57,53,0.2); border: 1px solid rgba(229,57,53,0.4);
    border-radius: 20px; font-size: 0.78rem; font-weight: 600;
    color: #ef9a9a; text-transform: capitalize;
  }

  .profile-header-info .email-text {
    font-size: 0.85rem; color: rgba(255,255,255,0.55); margin-top: 6px;
  }

  /* Form card */
  .form-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid var(--border);
    box-shadow: 0 2px 16px rgba(26,35,126,0.06);
    overflow: hidden;
    margin-bottom: 20px;
  }

  .form-card-header {
    padding: 18px 28px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 10px;
  }

  .form-card-header .icon-wrap {
    width: 34px; height: 34px; border-radius: 8px;
    background: rgba(26,35,126,0.08);
    display: flex; align-items: center; justify-content: center;
  }

  .form-card-header .icon-wrap i { color: var(--blue-dark); font-size: 16px; }

  .form-card-header h6 {
    font-size: 0.95rem; font-weight: 700; color: var(--blue-dark); margin: 0;
    font-family: 'Plus Jakarta Sans', sans-serif;
  }

  .form-card-body { padding: 24px 28px; }

  .field-group { margin-bottom: 18px; }

  .field-group label {
    display: block; font-size: 0.74rem; font-weight: 700;
    letter-spacing: 0.06em; text-transform: uppercase;
    color: var(--blue-dark); margin-bottom: 7px;
  }

  .input-wrap { position: relative; }

  .input-wrap .input-icon {
    position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
    color: #b0bcc8; font-size: 15px; pointer-events: none;
  }

  .field-group input, .field-group select {
    width: 100%; padding: 11px 14px 11px 40px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.9rem; color: var(--blue-dark);
    background: var(--cream); border: 1.5px solid var(--border);
    border-radius: 10px; outline: none;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
  }

  .field-group input:focus {
    background: #fff; border-color: var(--blue-light);
    box-shadow: 0 0 0 3px rgba(57,73,171,0.1);
  }

  .field-group input[readonly] {
    cursor: not-allowed; color: #9aabb8;
  }

  .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

  .btn-save {
    padding: 11px 28px;
    background: linear-gradient(135deg, var(--blue-dark), var(--blue-light));
    color: #fff; border: none; border-radius: 10px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.9rem; font-weight: 700; cursor: pointer;
    transition: all 0.2s;
  }

  .btn-save:hover { box-shadow: 0 6px 20px rgba(26,35,126,0.3); transform: translateY(-1px); }
  .btn-save:active { transform: translateY(0); }

  .btn-remove-photo {
    padding: 11px 20px;
    background: #fff; color: var(--red);
    border: 1.5px solid #fca5a5; border-radius: 10px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.87rem; font-weight: 600; cursor: pointer; transition: all 0.2s;
  }

  .btn-remove-photo:hover { background: #fff5f5; }

  .alert-success {
    background: #f0fff4; border: 1px solid #9ae6b4;
    border-left: 3px solid #38a169; border-radius: 10px;
    padding: 12px 16px; margin-bottom: 20px;
    font-size: 0.87rem; color: #276749;
    display: flex; align-items: center; gap: 8px;
  }

  /* Avatar preview kecil di form foto */
  .photo-preview-area {
    display: flex; align-items: center; gap: 16px;
    padding: 16px; background: var(--cream);
    border: 1.5px dashed var(--border); border-radius: 12px;
    margin-bottom: 18px;
  }

  .avatar-sm {
    width: 56px; height: 56px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; font-weight: 800; color: #fff;
    overflow: hidden; flex-shrink: 0;
    font-family: 'Plus Jakarta Sans', sans-serif;
  }

  .avatar-sm img { width: 100%; height: 100%; object-fit: cover; }

  .photo-info { flex: 1; }
  .photo-info p { font-size: 0.85rem; color: var(--text-mid); margin: 0; }
  .photo-info span { font-size: 0.78rem; color: #b0bcc8; }

  .btn-choose-photo {
    padding: 8px 16px; background: #fff;
    border: 1.5px solid var(--border); border-radius: 8px;
    font-size: 0.82rem; font-weight: 600; color: var(--blue-dark);
    cursor: pointer; transition: all 0.2s; white-space: nowrap;
  }

  .btn-choose-photo:hover { border-color: var(--blue-light); background: #f0f2ff; }

  @media (max-width: 640px) {
    .field-row { grid-template-columns: 1fr; }
    .profile-header-card { flex-direction: column; text-align: center; }
  }
</style>

<div class="profile-wrapper">

  {{-- Alert success --}}
  @if(session('success'))
    <div class="alert-success">
      <i class="ti ti-circle-check"></i> {{ session('success') }}
    </div>
  @endif

  {{-- Header card --}}
  <div class="profile-header-card">
    <div class="avatar-lg">
      <div class="avatar-circle-lg" id="headerAvatar"
           style="background: {{ auth()->user()->getAvatarColor() }}">
        @if(auth()->user()->photo)
          <img src="{{ Storage::url(auth()->user()->photo) }}" alt="foto profil">
        @else
          {{ auth()->user()->getInitials() }}
        @endif
      </div>
    </div>
    <div class="profile-header-info">
      <h4>{{ auth()->user()->name }}</h4>
      <div class="role-badge">{{ ucfirst(auth()->user()->role) }}</div>
      <div class="email-text">{{ auth()->user()->email }}</div>
    </div>
  </div>

  <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Foto Profil --}}
    <div class="form-card">
      <div class="form-card-header">
        <div class="icon-wrap"><i class="ti ti-camera"></i></div>
        <h6>Foto Profil</h6>
      </div>
      <div class="form-card-body">
        <div class="photo-preview-area">
          <div class="avatar-sm" id="previewAvatar"
               style="background: {{ auth()->user()->getAvatarColor() }}">
            @if(auth()->user()->photo)
              <img src="{{ Storage::url(auth()->user()->photo) }}" alt="preview" id="previewImg">
            @else
              <span id="previewInitials">{{ auth()->user()->getInitials() }}</span>
            @endif
          </div>
          <div class="photo-info">
            <p id="photoFileName">
              @if(auth()->user()->photo) Foto profil sudah diatur
              @else Belum ada foto — menggunakan inisial nama
              @endif
            </p>
            <span>JPG, PNG, WEBP. Maks 2MB</span>
          </div>
          <label class="btn-choose-photo" for="photoInput">
            <i class="ti ti-upload" style="margin-right:4px"></i> Pilih Foto
          </label>
          <input type="file" id="photoInput" name="photo" accept="image/*" style="display:none">
        </div>

        @if(auth()->user()->photo)
          <button type="submit" name="remove_photo" value="1" class="btn-remove-photo">
            <i class="ti ti-trash" style="margin-right:4px"></i> Hapus Foto (pakai inisial)
          </button>
        @endif
      </div>
    </div>

    {{-- Info Pribadi --}}
    <div class="form-card">
      <div class="form-card-header">
        <div class="icon-wrap"><i class="ti ti-user"></i></div>
        <h6>Informasi Pribadi</h6>
      </div>
      <div class="form-card-body">
        <div class="field-row">
          <div class="field-group">
            <label>Nama Lengkap</label>
            <div class="input-wrap">
              <span class="input-icon"><i class="ti ti-user"></i></span>
              <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required>
            </div>
            @error('name')<div style="font-size:.78rem;color:#c53030;margin-top:4px">{{ $message }}</div>@enderror
          </div>
          <div class="field-group">
            <label>No. Telepon</label>
            <div class="input-wrap">
              <span class="input-icon"><i class="ti ti-phone"></i></span>
              <input type="text" name="phone" placeholder="08xxxxxxxxxx"
                     value="{{ old('phone', auth()->user()->phone) }}">
            </div>
          </div>
        </div>
        <div class="field-group">
          <label>Email</label>
          <div class="input-wrap">
            <span class="input-icon"><i class="ti ti-mail"></i></span>
            <input type="email" value="{{ auth()->user()->email }}" readonly>
          </div>
        </div>
        <div class="field-group">
          <label>Role</label>
          <div class="input-wrap">
            <span class="input-icon"><i class="ti ti-shield"></i></span>
            <input type="text" value="{{ ucfirst(auth()->user()->role) }}" readonly>
          </div>
        </div>
      </div>
    </div>

    {{-- Ganti Password --}}
    <div class="form-card">
      <div class="form-card-header">
        <div class="icon-wrap"><i class="ti ti-lock"></i></div>
        <h6>Ganti Password</h6>
      </div>
      <div class="form-card-body">
        <p style="font-size:.85rem;color:#9aabb8;margin-bottom:16px">Kosongkan jika tidak ingin mengganti password.</p>
        <div class="field-row">
          <div class="field-group">
            <label>Password Baru</label>
            <div class="input-wrap">
              <span class="input-icon"><i class="ti ti-lock"></i></span>
              <input type="password" name="password" placeholder="Min. 6 karakter">
            </div>
            @error('password')<div style="font-size:.78rem;color:#c53030;margin-top:4px">{{ $message }}</div>@enderror
          </div>
          <div class="field-group">
            <label>Konfirmasi Password</label>
            <div class="input-wrap">
              <span class="input-icon"><i class="ti ti-lock-check"></i></span>
              <input type="password" name="password_confirmation" placeholder="Ulangi password baru">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div style="display:flex; justify-content:flex-end;">
      <button type="submit" class="btn-save">
        <i class="ti ti-device-floppy" style="margin-right:6px"></i> Simpan Perubahan
      </button>
    </div>

  </form>
</div>

<script>
  // Preview foto sebelum upload
  document.getElementById('photoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(ev) {
      const src = ev.target.result;

      // Update preview kecil
      const previewAvatar = document.getElementById('previewAvatar');
      previewAvatar.innerHTML = `<img src="${src}" alt="preview">`;

      // Update header avatar
      const headerAvatar = document.getElementById('headerAvatar');
      headerAvatar.innerHTML = `<img src="${src}" alt="preview">`;

      document.getElementById('photoFileName').textContent = file.name;
    };
    reader.readAsDataURL(file);
  });
</script>
@endsection
