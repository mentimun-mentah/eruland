<!DOCTYPE html>
<html>
<head>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />


    <title>eruland</title>
    <link rel="icon" href="" />

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Dela+Gothic+One&display=swap" rel="stylesheet" />

    <link href="{{ asset('css/fontawesome/css/all.css') }}" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <style>
      .navbar-vertical.navbar-expand-xs .navbar-nav>.nav-item>.nav-link.active {
        background: #a5a5a533;
      }
    </style>
  </head>

<body>
<div class="app">

  <nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
      <!-- Brand -->
      <div class="sidenav-header d-flex align-items-center">
        <a href="/" class="navbar-brand" style="font-family: 'Dela Gothic One', cursive; color:#172b4d;" >
          <span class="va-sub ml-2">ERULAND</span>
        </a>

        <div class="ml-auto">
          <!-- Sidenav toggler -->
          <div class="sidenav-toggler d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
            <div class="sidenav-toggler-inner mr-4">
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
            </div>
          </div>
        </div>
        <!-- /ml-auto -->
      </div>
      <!-- /sidenav-header -->

      <div class="navbar-inner">
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
          <!-- Nav items -->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="{{url('/')}}">
                <i class="far fa-door-open"></i>
                <span class="nav-link-text">Home</span>
              </a>
            </li>
            <li class="nav-item {{Route::currentRouteName() == 'admin.transaction' ? 'active': ''}}">
              <a class="nav-link" href="{{url('/admin/transaction')}}">
                <i class="far fa-sack-dollar"></i>
                <span class="nav-link-text">Transaksi</span>
              </a>
            </li>
          </ul>
          <!-- Nav items -->
        </div><!-- /collapese -->
      </div><!-- /navbar-inner -->

    </div>
  </nav><!-- /sidenav -->

  <!-- Main content -->
  <div class="main-content" id="panel">
    <!-- Topnav -->
    <nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
      <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <!-- Navbar links -->
          <ul class="navbar-nav align-items-center ml-md-auto">
            <li class="nav-item d-xl-none">
              <!-- Sidenav toggler -->
              <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
                <div class="sidenav-toggler-inner mr-4">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </div>
            </li>
            <li class="nav-item d-sm-none">
              <a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
                <i class="far fa-search"></i>
              </a>
            </li>
          </ul>
          <ul class="navbar-nav align-items-center ml-auto ml-md-0">
            <li class="nav-item">
              <a class="nav-link pr-0" href="#" role="button">
                <div class="media align-items-center">
                  <span class="avatar avatar-sm rounded-circle">
                    <img alt="Image placeholder" src="{{asset('storage/avatar/'.Auth::user()->avatar)}}">
                  </span>
                  <div class="media-body ml-2 d-none d-lg-block">
                    <span class="mb-0 text-sm  font-weight-bold">{{ ucfirst(Auth::user()->name) }}</span>
                  </div>
                </div>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- Header -->

    <main>
      @yield('content')
    </main>

  </div><!-- /main-content -->
</div>

  <!-- Scripts -->
  <script src="{{ mix('js/app.js') }}"></script>
  <script src="{{ asset('js/cookie.js') }}"></script>
  <script src="{{ asset('js/admin.js') }}"></script>
  @include('sweetalert::alert')
</body>
</html>

