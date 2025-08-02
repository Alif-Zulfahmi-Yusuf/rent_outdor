<nav class="layout-navbar shadow-none py-0">
    <div class="container">
        <div class="navbar navbar-expand-lg landing-navbar px-3 px-md-8">
            <!-- Menu logo wrapper: Start -->
            <div class="navbar-brand app-brand demo d-flex py-0 py-lg-2 me-4 me-xl-8">
                <a href="{{ route('home') }}" class="app-brand-link">
                    <img src="{{ asset('assets/img/logo.svg') }}" alt="logo" width="32">
                    <span class="app-brand-text demo menu-text fw-bold ms-2 ps-1">{{ config('app.name') }}</span>
                </a>
            </div>
            <!-- Menu logo wrapper: End -->
            <!-- Menu wrapper: Start -->
            <div class="collapse navbar-collapse landing-nav-menu" id="navbarSupportedContent">
                <button class="navbar-toggler border-0 text-heading position-absolute end-0 top-0 scaleX-n1-rtl"
                    type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="ti ti-x ti-lg"></i>
                </button>
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link me-2 fw-medium {{ request()->routeIs('barangs') ? 'active' : '' }}"
                            href="{{ route('barangs') }}">
                            <i class="ti ti-book"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('barangs') }}" method="get">
                            <div class="input-group input-group-merge rounded-pill">
                                <span class="input-group-text" id="basic-addon-search31"><i
                                        class="ti ti-search"></i></span>
                                <input type="text" class="form-control rounded-e-pill" name="search"
                                    placeholder="Cari buku" aria-label="Cari buku"
                                    aria-describedby="basic-addon-search31">
                            </div>
                        </form>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-medium ms-2 {{ request()->routeIs('bags.*') ? 'active' : '' }}"
                            href="{{ route('bags.index') }}">
                            <i class="ti ti-shopping-bag"></i>
                            @if (Auth::user() && Auth::user()->bags->count() > 0)
                            <span class="badge rounded-pill bg-label-danger badge-notifications">
                                {{ Auth::user()->bags->count() }}
                            </span>
                            @endif
                        </a>
                    </li>
                </ul>
            </div>
            <div class="landing-menu-overlay d-lg-none"></div>
            <!-- Menu wrapper: End -->
            <!-- Toolbar: Start -->
            <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- navbar button: Start -->
                <li class="nav-item d-block d-lg-none">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#modalSearch">
                        <i class="ti ti-search"></i>
                    </a>
                </li>
                <li class="nav-item d-block d-lg-none">
                    <a class="nav-link" href="{{ route('bags.index') }}">
                        <i class="ti ti-shopping-bag"></i>
                        @if (Auth::user() && Auth::user()->bags->count() > 0)
                        <span class="badge rounded-pill bg-label-danger badge-notifications">
                            {{ Auth::user()->bags->count() }}
                        </span>
                        @endif
                    </a>
                </li>
                @auth
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 text-end d-none d-md-block">
                                <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                            </div>
                            <div class="flex-shrink-0 ms-2">
                                <div class="avatar">
                                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}"
                                        class="rounded-circle">
                                </div>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item mt-0 waves-effect" href="#">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-2">
                                        <div class="avatar">
                                            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}"
                                                class="rounded-circle">
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                                        <small class="text-muted">{{ Auth::user()->email }}</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider my-1 mx-n2"></div>
                        </li>
                        <li>
                            <a class="dropdown-item waves-effect" href="{{ route('account.index') }}">
                                <i class="ti ti-user me-3 ti-md"></i><span class="align-middle">Akun Saya</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item waves-effect" href="{{ route('account.index') }}">
                                <i class="ti ti-barangs me-3 ti-md"></i><span class="align-middle">Peminjaman</span>
                            </a>
                        </li>
                        <li>
                            <div class="d-grid px-2 pt-2 pb-1">
                                <a class="btn btn-sm btn-danger d-flex waves-effect waves-light"
                                    href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <small class="align-middle">Logout</small>
                                    <i class="ti ti-logout ms-2 ti-14px"></i>
                                </a>
                            </div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
                @else
                <li class="d-none d-lg-block">
                    <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary">
                        Daftar
                    </a>
                </li>
                @endauth
                <!-- navbar button: End -->
            </ul>
            <!-- Toolbar: End -->
        </div>
    </div>
</nav>
