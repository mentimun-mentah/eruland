<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>ERULAND</title>
    <link rel="icon" href="{{asset('storage/logo.png')}}" />

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com" />
    <link
      href="https://fonts.googleapis.com/css?family=Nunito"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Dela+Gothic+One&display=swap"
      rel="stylesheet"
    />
    <link href="{{ asset('css/fontawesome/css/all.css') }}" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/css/bootstrap-select.min.css">
    <script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{env('MIDTRANS_CLIENTKEY')}}"></script>

    <style>
      .select-custom .bootstrap-select .dropdown-toggle{
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
      }
      .has-search .form-control {
        padding-left: 2.375rem;
      }
      .has-search .form-control-feedback {
        position: absolute;
        z-index: 2;
        display: block;
        width: 2.375rem;
        height: 2.375rem;
        line-height: 2.375rem;
        text-align: center;
        pointer-events: none;
        color: #aaa;
      }
      /***** Footer *****/
      .footer-logo-text {
        color: #2c3e50;
      }
      .footer-top {
        padding: 60px 0;
        background: #fff;
        text-align: left;
        color: #757575;
      }
      .footer-top h3 {
        padding-bottom: 10px;
        color: #2c3e50;
      }
      .footer-about img.logo-footer {
        max-width: 74px;
        margin-top: 0;
        margin-bottom: 18px;
      }
      .footer-about p a {
        color: #aaa;
        border-bottom: 1px dashed #666;
      }
      .footer-about p a:hover,
      .footer-about p a:focus {
        color: #fff;
        border-color: #aaa;
      }
      .footer-contact p {
        word-wrap: break-word;
      }
      .footer-contact i {
        padding-right: 10px;
        font-size: 18px;
        color: #666;
      }
      .footer-contact p a {
        color: #aaa;
        border-bottom: 1px dashed #666;
      }
      .footer-contact p a:hover,
      .footer-contact p a:focus {
        color: #2c3e50;
        border-color: #aaa;
        text-decoration: none;
      }
      .footer-social a {
        display: inline-block;
        margin-right: 20px;
        margin-bottom: 8px;
        color: #777;
        border: 0;
      }
      .footer-social a:hover,
      .footer-social a:focus {
        color: #aaa;
        border: 0;
      }
      .footer-social i {
        font-size: 24px;
        vertical-align: middle;
      }
      .footer-bottom {
        padding: 15px 0;
        background: #f5f5f5;
        text-align: left;
        color: #757575;
      }
      .footer-copyright p {
        margin: 0;
        padding: 0.5rem 0;
      }
      .footer-copyright a {
        color: #fff;
        border: 0;
      }
      .footer-copyright a:hover,
      .footer-copyright a:focus {
        color: #aaa;
        border: 0;
      }
      /***** Footer *****/
      /***** Navbar ******/
      .va-sub {
        vertical-align: sub;
      }
      .badge-wrapper {
        position: relative;
      }

      .badge.badge-notification {
        position: absolute;
        top: -10px;
        right: -8px;
        display: inline-block;
        width: auto;
        height: 15px;
        border-radius: calc(15px / 2);
        background-color: #ff4d4f;
        padding-top: 4px;
        font-size: 9px;
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
      }
      /***** Navbar ******/

      .hover-pointer:hover {
        cursor: pointer;
      }

      .side-nav-link {
        padding-left: 0;
        padding-right: 0;
        color: rgba(0, 0, 0, 0.65);
      }
      .side-nav-link.active {
        color: rgba(0, 0, 0);
        font-weight: 700;
      }

      @media only screen and (max-width: 575px) {
        .account.pt-4.pb-4 {
          padding-top: 0 !important;
          padding-bottom: 0 !important;
        }
        .account .card.card-container {
          border: 0;
        }
      }

      .file-btn {
        position: relative;
        overflow: hidden;
        cursor: pointer;
      }
      .fileupload {
        cursor: pointer;
        position: absolute;
        font-size: 50px;
        opacity: 0;
        right: 0;
        top: 0;
      }

    </style>
  </head>
  <body>
    <div class="app">
      <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
          <a class="navbar-brand" href="{{ url('/') }}" style="font-family: 'Dela Gothic One', cursive; color:#2c3e50;">
            <img
              src="{{asset('storage/logo.png')}}"
              alt="eruland"
              width="140"
              height="30"
              class="d-inline-block py-0"
              style="vertical-align: initial"
            />
          </a>
          <button
            class="navbar-toggler"
            type="button"
            data-toggle="collapse"
            data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="{{ __('Toggle navigation') }}"
          >
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav align-items-center w-100">

              <li class="nav-item">
                <a class="nav-link" href="{{ url('/') }}">
                  Beranda
                </a>
              </li>

              <li class="nav-item">
                <div class="dropdown">
                  <a 
                    class="nav-link"
                    href="#"
                    role="button"
                    id="dropdownMenuLink"
                    data-toggle="dropdown"
                    aria-haspopup="true" 
                    aria-expanded="false"
                  >
                    Kategori
                  </a>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="{{url('/?category=kering')}}">Kering</a>
                    <a class="dropdown-item" href="{{url('/?category=basah')}}">Basah</a>
                  </div>
                </div>
              </li>

              <li class="nav-item w-100 mx-3">
                <form class="form-inline w-100" action="{{url('/')}}">
                  <div class="form-group has-search w-100">
                    <span class="fa fa-search form-control-feedback"></span>
                    <input type="search" name="q" class="form-control w-100" placeholder="Cari produk" />
                  </div>
                </form>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="{{url('/carts/my-cart')}}">
                  <i class="far fa-shopping-cart fa-lg badge-wrapper">
                    <span class="badge badge-notification badge-secondary">
                      {{Cart::instance('cart')->content()->count()}}
                    </span>
                  </i>
                </a>
              </li>

              <span class="border mx-3" style="height: 35px"></span>

              <!-- Authentication Links -->
              @guest @if (Route::has('login'))
              <li class="nav-item {{Request::is('login') ? 'active': ''}}">
                <a class="nav-link py-lg-0 pr-lg-0" href="{{ route('login') }}">
                  <button type="button" class="btn btn-outline-dark">
                    Masuk
                  </button>
                </a>
              </li>
              @endif @if (Route::has('register'))
              <li class="nav-item {{Request::is('register') ? 'active': ''}}">
                <a class="nav-link py-lg-0" href="{{ route('register') }}">
                  <button type="button" class="btn btn-danger">Daftar</button>
                </a>
              </li>
              @endif @else
              <li class="nav-item dropdown">
                <a
                  id="navbarDropdown"
                  class="nav-link dropdown-toggle"
                  href="#"
                  role="button"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                  v-pre
                >
                  <img 
                    src="{{asset('storage/avatar/'.Auth::user()->avatar)}}" 
                    class="rounded rounded-circle mr-1" 
                    alt="profile"
                    width="30"
                    height="30"
                  > {{ ucfirst(Auth::user()->name) }}
                </a>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                  <div class="text-center">Selamat Datang</div>
                  <div class="dropdown-divider"></div>

                  <a
                    class="dropdown-item"
                    href="{{url('/account/profile')}}"
                  >
                    Akun
                  </a>
                  @if(Auth::user()->role == 'admin')
                  <a
                    class="dropdown-item"
                    href="{{url('/admin/transaction')}}"
                  >
                    Admin
                  </a>
                  @endif
                  <a class="dropdown-item" href="{{url('/account/orders')}}"> 
                    Pesanan Saya
                  </a>
                  <a
                    class="dropdown-item"
                    href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();"
                  >
                    {{ __('Keluar') }}
                  </a>

                  <form
                    id="logout-form"
                    action="{{ route('logout') }}"
                    method="POST"
                    class="d-none"
                  >
                    @csrf
                  </form>
                </div>
              </li>
              @endguest

            </ul>
          </div><!--/collapse-->

        </div>
      </nav>

      <main class="py-4">
        @yield('content')
      </main>

      <!-- FOOTER -->
      <footer>
        <div class="footer-top">
          <div class="container">
            <div class="row">
              <div class="col-md-4 col-lg-4 footer-about wow fadeInUp">
                <h1 class="footer-logo-text">Eruland</h1>
                <p>
                  Eruland adalah sebuah website yang bertujuan sebagai wadah untuk membantu petani rumput laut untuk menjual hasil dari panen rumput laut 
                </p>
                <p class="mb-0">
                  Copyright © {{date("Y")}} All Rights Reserved by Eruland.
                </p>
              </div>
              <div
                class="col-md-4 col-lg-4 offset-lg-1 footer-contact wow fadeInDown"
              >
                <h3>Contact us</h3>
                <p>
                  <i class="fas fa-map-marker-alt"></i> Jimbaran, Karangasem, Ubung
                </p>
                <p><i class="fas fa-phone"></i> Phone: 0821 3332 1268</p>
                <p>
                  <i class="fas fa-envelope"></i> Email:
                  <a href="mailto:doca-support@gmail.com">eruland-support@gmail.com</a>
                </p>
              </div>
              <div class="col-md-4 col-lg-3 footer-social wow fadeInUp">
                <h3>Follow us</h3>
                <p>
                  <a target="__blank" href="https://www.facebook.com"><i class="fab fa-facebook"></i></a>
                  <a target="__blank" href="https://www.twitter.com"><i class="fab fa-twitter"></i></a>
                  <a target="__blank" href="https://www.google.com"><i class="fab fa-google-plus-g"></i></a>
                  <a target="__blank" href="https://www.instagram.com"><i class="fab fa-instagram"></i></a>
                </p>
              </div>
            </div>
          </div>
        </div>
      </footer>
      <!-- FOOTER -->
    </div>
    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/js/bootstrap-select.min.js"></script>
    <script type="text/javascript">window.$crisp=[];window.CRISP_WEBSITE_ID="47485c17-03ec-46ce-81ac-c5016092870f";(function(){d=document;s=d.createElement("script");s.src="https://client.crisp.chat/l.js";s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})();</script>

    @include('sweetalert::alert')
  </body>
</html>

