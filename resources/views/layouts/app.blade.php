<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>@yield('title', 'Home')</title>
      <!-- [Meta] -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
  <meta name="keywords" content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
  <meta name="author" content="CodedThemes">

  <!-- [Favicon] icon -->
  <link rel="icon" href="{{ asset('assets/images/Scomptec.png') }}" type="image/x-icon"> <!-- [Google Font] Family -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
  <!-- [Tabler Icons] https://tablericons.com -->
  <link rel="stylesheet" href="{{ asset('assets/css/tabler-icons.min.css') }}">
  <!-- [Feather Icons] https://feathericons.com -->
  <link rel="stylesheet" href="{{ asset('assets/css/feather.css') }}">
  <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
  <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.css') }}">
  <!-- [Material Icons] https://fonts.google.com/icons -->
  <link rel="stylesheet" href="{{ asset('assets/css/material.css') }}">
  <!-- [Template CSS Files] -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
  <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}">

</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
  <!-- [ Pre-loader ] start -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <!-- [ Pre-loader ] End -->
  <!-- [ Sidebar Menu ] start -->
  <nav class="pc-sidebar">
    <div class="navbar-wrapper">
      <div class="m-header">
        <a href="home" class="b-brand text-primary">
          <!-- ========   Change your logo from here   ============ -->
          <img src="{{ asset('assets/images/Scomptec.png') }}" style="width:150px;">
        </a>
      </div>
      <div class="navbar-content">
        <ul class="pc-navbar">
            {{-- Dashboard - semua role --}}
            <li class="pc-item">
                <a href="{{ url('/home') }}" class="pc-link">
                    <span class="pc-micon"><i class="ti ti-building-bank"></i></span>
                    <span class="pc-mtext">Home</span>
                </a>
            </li>

            {{-- Kasir - admin, kasir, user --}}
            @auth
            @if(Auth::user()->hasRole('admin', 'kasir', 'user'))
            <li class="pc-item">
                <a href="{{ url('/kasir') }}" class="pc-link {{ request()->is('kasir*') ? 'active' : '' }}">
                    <span class="pc-micon"><i class="ti ti-calculator"></i></span>
                    <span class="pc-mtext">Kasir</span>
                </a>
            </li>
            @endif
            @endauth

            {{-- Data Kelas - admin & kasir --}}
            @auth
            @if(Auth::user()->hasRole('admin', 'kasir'))
            <li class="pc-item">
                <a href="{{ route('products.index') }}"
                  class="pc-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <span class="pc-micon"><i class="ti ti-book"></i></span>
                    <span class="pc-mtext">Program Khusus</span>
                </a>
            </li>
            @endif
            @endauth


        </ul>
        
      </div>
    </div>
  </nav>
  <!-- [ Sidebar Menu ] end --> <!-- [ Header Topbar ] start -->
  <header class="pc-header">
    <div class="header-wrapper"> <!-- [Mobile Media Block] start -->
      <div class="me-auto pc-mob-drp">
        <ul class="list-unstyled">
          <!-- ======= Menu collapse Icon ===== -->
          <li class="pc-h-item pc-sidebar-collapse">
            <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
              <i class="ti ti-menu-2"></i>
            </a>
          </li>
          <li class="pc-h-item pc-sidebar-popup">
            <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
              <i class="ti ti-menu-2"></i>
            </a>
          </li>
          <li class="dropdown pc-h-item d-inline-flex d-md-none">
            <a class="pc-head-link dropdown-toggle arrow-none m-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
              <i class="ti ti-search"></i>
            </a>
            <div class="dropdown-menu pc-h-dropdown drp-search">
              <form class="px-3">
                <div class="form-group mb-0 d-flex align-items-center">
                  <i data-feather="search"></i>
                  <input type="search" class="form-control border-0 shadow-none" placeholder="Search here. . .">
                </div>
              </form>
            </div>
          </li>
          <li class="pc-h-item d-none d-md-inline-flex">
            <form class="header-search">
              <i data-feather="search" class="icon-search"></i>
              <input type="search" id="search-product" class="form-control" placeholder="Search here. . .">
            </form>
          </li>
        </ul>
      </div>
      <!-- [Mobile Media Block end] -->
      <div class="ms-auto">
        <ul class="list-unstyled">
          @auth
          <li class="dropdown pc-h-item">
            <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
              <i class="ti ti-mail"></i>
            </a>
            <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
              <div class="dropdown-header d-flex align-items-center justify-content-between">
                <h5 class="m-0">Message</h5>
                <a href="#!" class="pc-head-link bg-transparent"><i class="ti ti-x text-danger"></i></a>
              </div>
              <div class="dropdown-divider"></div>
              <div class="dropdown-header px-0 text-wrap header-notification-scroll position-relative" style="max-height: calc(100vh - 215px)">
                <div class="list-group list-group-flush w-100">
                  <a class="list-group-item list-group-item-action">
                    <div class="d-flex">
                      <div class="flex-shrink-0">
                        <img src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="user-image" class="user-avtar">
                      </div>
                      <div class="flex-grow-1 ms-1">
                        <span class="float-end text-muted">3:00 AM</span>
                        <p class="text-body mb-1">It's <b>Cristina danny's</b> birthday today.</p>
                        <span class="text-muted">2 min ago</span>
                      </div>
                    </div>
                  </a>
                  <a class="list-group-item list-group-item-action">
                    <div class="d-flex">
                      <div class="flex-shrink-0">
                        <img src="{{ asset('assets/images/user/avatar-1.jpg') }}" alt="user-image" class="user-avtar">
                      </div>
                      <div class="flex-grow-1 ms-1">
                        <span class="float-end text-muted">6:00 PM</span>
                        <p class="text-body mb-1"><b>Aida Burg</b> commented your post.</p>
                        <span class="text-muted">5 August</span>
                      </div>
                    </div>
                  </a>
                  <a class="list-group-item list-group-item-action">
                    <div class="d-flex">
                      <div class="flex-shrink-0">
                        <img src="{{ asset('assets/images/user/avatar-3.jpg') }}" alt="user-image" class="user-avtar">
                      </div>
                      <div class="flex-grow-1 ms-1">
                        <span class="float-end text-muted">2:45 PM</span>
                        <p class="text-body mb-1"><b>There was a failure to your setup.</b></p>
                        <span class="text-muted">7 hours ago</span>
                      </div>
                    </div>
                  </a>
                  <a class="list-group-item list-group-item-action">
                    <div class="d-flex">
                      <div class="flex-shrink-0">
                        <img src="{{ asset('assets/images/user/avatar-4.jpg') }}" alt="user-image" class="user-avtar">
                      </div>
                      <div class="flex-grow-1 ms-1">
                        <span class="float-end text-muted">9:10 PM</span>
                        <p class="text-body mb-1"><b>Cristina Danny </b> invited to join <b> Meeting.</b></p>
                        <span class="text-muted">Daily scrum meeting time</span>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
              <div class="dropdown-divider"></div>
              <div class="text-center py-2">
                <a href="#!" class="link-primary">View all</a>
              </div>
            </div>
          </li>
          @endauth
          @auth
          <li class="dropdown pc-h-item header-user-profile">
            <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
              {{-- Avatar: foto jika ada, inisial jika tidak --}}
              @if(Auth::user()->photo)
                <img src="{{ Storage::url(Auth::user()->photo) }}"
                     class="user-avtar rounded-circle"
                     style="width:35px;height:35px;object-fit:cover;">
              @else
                <div style="
                  width:35px;height:35px;border-radius:50%;
                  background:{{ Auth::user()->getAvatarColor() }};
                  display:flex;align-items:center;justify-content:center;
                  font-size:0.75rem;font-weight:800;color:#fff;
                  font-family:'Plus Jakarta Sans',sans-serif;flex-shrink:0;">
                  {{ Auth::user()->getInitials() }}
                </div>
              @endif
              <span style="margin-left:8px">{{ Auth::user()->name ?? 'Guest' }}</span>
            </a>

            <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
              {{-- Header dropdown --}}
              <div class="dropdown-header">
                <div class="d-flex align-items-center mb-1">
                  <div class="flex-shrink-0">
                    @if(Auth::user()->photo)
                      <img src="{{ Storage::url(Auth::user()->photo) }}"
                           class="rounded-circle"
                           style="width:40px;height:40px;object-fit:cover;">
                    @else
                      <div style="
                        width:40px;height:40px;border-radius:50%;
                        background:{{ Auth::user()->getAvatarColor() }};
                        display:flex;align-items:center;justify-content:center;
                        font-size:0.85rem;font-weight:800;color:#fff;
                        font-family:'Plus Jakarta Sans',sans-serif;">
                        {{ Auth::user()->getInitials() }}
                      </div>
                    @endif
                  </div>
                  <div class="flex-grow-1 ms-3">
                    <h6 class="mb-0" style="font-size:0.9rem;font-weight:700">{{ Auth::user()->name }}</h6>
                    <span style="font-size:0.78rem;color:#8c9ec0;text-transform:capitalize">{{ Auth::user()->role }}</span>
                  </div>
                </div>
              </div>

              <div style="padding:4px 8px">
                <a href="{{ route('profile.edit') }}" class="dropdown-item" style="border-radius:8px">
                  <i class="ti ti-user-edit"></i>
                  <span>Edit Profil</span>
                </a>
                <div style="height:1px;background:#f0f2f5;margin:4px 0"></div>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="dropdown-item" style="border-radius:8px;color:#e53935">
                    <i class="ti ti-power"></i>
                    <span>Logout</span>
                  </button>
                </form>
              </div>

            </div>
          </li>
          @endauth
        </ul>
      </div>
    </div>
  </header>
  <!-- [ Header ] end -->

  <!-- [ Main Content ] start -->
  <div class="pc-container">
    <div class="pc-content">
        @yield('content')
      </div>
  </div>
      <!-- [ Main Content ] end -->
  <footer class="pc-footer">
    <div class="footer-wrapper container-fluid">
      <div class="row">
        <div class="col-sm my-1">
          <p class="m-0">PT Scomptec Edukom Persada</p>
        </div>
        <div class="col-sm my-1">
          <p class="m-0">PT Scomptec Edukom Persada</p>
        </div>
      </div>
    </div>
  </footer>

  <!-- Required JS -->
<script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>

<!-- Chart Library -->
<script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>

<!-- Core Template -->
<script src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>
<script src="{{ asset('assets/js/pcoded.js') }}"></script>


<!-- Feather init (WAJIB untuk SVG/icon) -->
<script>feather.replace();</script>
<script>layout_change('light');</script>
<script>change_box_container('false');</script>
<script>layout_rtl_change('false');</script>
<script>preset_change("preset-1");</script>
<script>font_change("Public-Sans");</script>

@yield('scripts')
</body>
<!-- [Body] end -->
</html>