@extends('layouts.app')
@section('content')
<div class="bg-light">
  <div class="container pt-4 pb-4 account">
    <div class="row">
      <div class="flex-column col-md-2 d-none d-lg-block pl-2 fs-14 nav">
        <a href="#"
          class="text-truncate text-dark align-middle text-decoration-none px-0 mb-3 nav-link"
          role="button"
        >
          <img width="40" height="40" src="{{asset('storage/avatar/'.Auth::user()->avatar)}}" class="rounded-circle" />
          <span class="pl-1 align-middle">
            {{ ucfirst(Auth::user()->name) }}
          </span>
        </a>
        <a href="{{url('/account/profile')}}"
          class="side-nav-link nav-link {{Route::currentRouteName() == 'account.profile' ? 'active': ''}}"
          role="button"
        >
          <span>
            <i class="far fa-user-circle mr-2"></i>Akun Saya
          </span>
        </a>

        @if(Auth::user()->role == 'penjual')
        <a href="{{url('/products/add-product')}}"
          class="side-nav-link nav-link {{Route::currentRouteName() == 'product.add_product' ? 'active': ''}}"
          role="button"
        >
          <span>
            <i class="far fa-shopping-bag mr-2"></i>Tambah Produk
          </span>
        </a>

        <a href="{{url('/products/my-product')}}"
          class="side-nav-link nav-link {{Route::currentRouteName() == 'product.my_product' ? 'active': ''}}"
          role="button"
        >
          <span>
            <i class="far fa-box-full mr-2"></i>Kelola Produk
          </span>
        </a>

        <a href="{{url('/account/orders-incoming')}}"
          class="side-nav-link nav-link {{Route::currentRouteName() == 'account.orders_incoming' ? 'active': ''}}"
          role="button"
        >
          <span>
            <i class="far fa-inbox-in mr-2"></i>Pesanan Masuk
          </span>
        </a>


        @endif

        <a href="{{url('/account/orders')}}"
          class="side-nav-link nav-link {{Route::currentRouteName() == 'account.orders' ? 'active': ''}}"
          role="button"
        >
          <span>
            <i class="far fa-clipboard-list mr-2"></i>Pesanan Saya
          </span>
        </a>
        <a href="{{url('/account/review')}}"
          class="side-nav-link nav-link {{Route::currentRouteName() == 'account.review' ? 'active': ''}}"
          role="button"
        >
          <span>
            <i class="far fa-file-signature mr-2"></i>Ulasan
          </span>
        </a>
        <a href="{{url('/account/password')}}"
          class="side-nav-link nav-link {{Route::currentRouteName() == 'account.password' ? 'active': ''}}"
          role="button"
        >
          <span>
            <i class="far fa-lock mr-2"></i>Atur Kata Sandi
          </span>
        </a>
      </div><!--/flex-column-->

      <div class="bg-white px-0 rounded h-100 col-lg-10 col-md-12">
        @yield('sidenav')
      </div>

    </div> <!--/row-->
  </div> <!--/container-->
</div>
<!--/bg-light-->

@endsection
