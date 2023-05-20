<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('Dapoer Van Kriwil', 'Dapoer Van Kriwil') }}</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">


    <link rel="stylesheet" href="{{ asset('css/tooplate-style.css') }}">
    @stack('css_after')
</head>
<body style="height: 3000px">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/home') }}">
                    {{ config('Dapoer Van Kriwil', 'Dapoer Van Kriwil') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>



<div class="container-lg">
    <div class="row">
        <div class="col-lg-3 mt-3">
            <div class="flex-shrink-0 p-3" style="width: 280px;">
                <ul class="list-unstyled ps-0">
                  <li class="mb-1">
                    @role('pemilik|koki|staff_pembelian')
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                        <i class="bi bi-list"></i> Kelola Data Master
                    </button>
                    @endrole
                    <div class="collapse show" id="home-collapse">
                      <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        @role('pemilik|koki|staff_pembelian')<li class="mb-1 mt-1 ms-4"><a href="/menus" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Menu</a></li>@endrole
                        @role('pemilik|staff_pembelian')<li class="mb-1 mt-1 ms-4"><a href="/bahan_bakus" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Bahan baku</a></li>@endrole
                        @role('pemilik|staff_pembelian')<li class="mb-1 mt-1 ms-4"><a href="/suppliers" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Supplier</a></li>@endrole
                      </ul>
                    </div>
                  </li>
                  <li class="mb-1">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#dashboard-collapse" aria-expanded="false">
                        <i class="bi bi-list"></i> Transaksi
                    </button>
                    <div class="collapse" id="dashboard-collapse">
                      <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        @role('pemilik|kasir')<li class="mb-1 mt-1 ms-4"><a href="/penjualan_menus" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Penjualan Menu</a></li>@endrole
                        @role('pemilik|staff_pembelian')<li class="mb-1 mt-1 ms-4"><a href="/pembelian_bahan_bakus" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Pembelian Bahan Baku</a></li>@endrole
                        @role('pemilik|staff_pembelian')<li class="mb-1 mt-1 ms-4"><a href="/pembelian_menu_suppliers" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Pembelian Menu Supplier</a></li>@endrole
                        @role('pemilik|koki')<li class="mb-1 mt-1 ms-4"><a href="/waste_menus" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Waste Menu</a></li>@endrole
                      </ul>
                    </div>
                  </li>
                  <li class="mb-1">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#orders-collapse" aria-expanded="false">
                        <i class="bi bi-list"></i> Laporan
                    </button>
                    <div class="collapse" id="orders-collapse">
                      <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        @role('pemilik|kasir')<li class="mb-1 mt-1 ms-4"><a href="/laporan_penjualan_menus" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Laporan Penjualan Menu</a></li>@endrole
                        @role('pemilik|staff_pembelian')<li class="mb-1 mt-1 ms-4"><a href="/laporan_pembelian_bahan_bakus" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Laporan Pembelian Bahan Baku</a></li>@endrole
                        @role('pemilik|staff_pembelian')<li class="mb-1 mt-1 ms-4"><a href="/laporan_pembelian_menu_suppliers" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Laporan Pembelian Menu Supplier</a></li>@endrole
                        @role('pemilik|kasir')<li class="mb-1 mt-1 ms-4"><a href="/laporan_rating_menus" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Laporan Rating Menu</a></li>@endrole
                        @role('pemilik|koki')<li class="mb-1 mt-1 ms-4"><a href="/laporan_waste_menus" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Laporan Waste Menu</a></li>@endrole
                      </ul>
                    </div>
                  </li>
                  <li class="border-top my-3"></li>
                </ul>
              </div>
        </div>
        <div class="col-lg-9 mt-4">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                    <div class="card-body">
                        @yield('content')
                    </div>
            </div>
        </div>
        </div>
    </div>
</div>
        <main class="py-4">

        </main>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('js_after')
</body>
</html>
