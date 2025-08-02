<nav class="navbar navbar-expand-lg navbar-light px-0">
    <div class="row gx-0 gy-2 w-100 flex-between-center">
        <div class="col-auto"><a class="text-decoration-none" href="{{ route('home') }}">
                <div class="d-flex align-items-center"><img src="{{ asset('assets/img/icons/logo.png') }}" alt="phoenix"
                        width="27" />
                    <h5 class="logo-text ms-2">{{ config('app.name') }}</h5>
                </div>
            </a></div>
        <div class="col-auto order-md-1">
            <ul class="navbar-nav navbar-nav-icons flex-row me-n2">
                <li class="nav-item d-flex align-items-center">
                    <div class="theme-control-toggle fa-icon-wait px-2">
                        <input class="form-check-input ms-0 theme-control-toggle-input" type="checkbox"
                            data-theme-control="phoenixTheme" value="dark" id="themeControlToggle" />
                        <label class="mb-0 theme-control-toggle-label theme-control-toggle-light"
                            for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left"
                            data-bs-title="Switch theme" style="height:32px;width:32px;"><span class="icon"
                                data-feather="moon"></span></label>
                        <label class="mb-0 theme-control-toggle-label theme-control-toggle-dark"
                            for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left"
                            data-bs-title="Switch theme" style="height:32px;width:32px;"><span class="icon"
                                data-feather="sun"></span></label>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2  {{ request()->routeIs('barangs.*') ? 'active' : '' }}"
                        href="{{ route('barangs') }}" role="button">
                        <span class="text-body-tertiary" data-feather="box" style="height:20px;width:20px;"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2 icon-indicator icon-indicator-primary  {{ request()->routeIs('bags.*') ? 'active' : '' }}"
                        href="{{ route('bags.index') }}" role="button">
                        <span class="text-body-tertiary" data-feather="shopping-cart"
                            style="height:20px;width:20px;"></span>
                        @if (Auth::user() && Auth::user()->bags->count() > 0)
                            <span class="icon-indicator-number">
                                {{ Auth::user()->bags->count() }}
                            </span>
                        @endif
                    </a>
                </li>
                @auth
                    <li class="nav-item dropdown"><a class="nav-link px-2" id="navbarDropdownUser" href="#"
                            role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true"
                            aria-expanded="false"><span class="text-body-tertiary" data-feather="user"
                                style="height:20px;width:20px;"></span></a>
                        <div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-profile shadow border mt-2"
                            aria-labelledby="navbarDropdownUser">
                            <div class="card position-relative border-0">
                                <div class="card-body p-0">
                                    <div class="text-center pt-4 pb-3">
                                        <div class="avatar avatar-xl ">
                                            <img class="rounded-circle "
                                                src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}"
                                                alt="" />

                                        </div>
                                        <h6 class="mt-2 text-body-emphasis">{{ Auth::user()->name }}</h6>
                                    </div>

                                </div>
                                <div class="overflow-auto scrollbar" style="height: 5rem;">
                                    <ul class="nav d-flex flex-column mb-2 pb-1">
                                        <li class="nav-item">
                                            <a class="nav-link px-3 d-block" href="{{ route('account.index') }}">
                                                <span class="me-2 text-body align-bottom" data-feather="user"></span>
                                                <span>Profile</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-footer p-0 border-top border-translucent">
                                    <ul class="nav d-flex flex-column my-3">
                                        <li class="nav-item"><a class="nav-link px-3 d-block"
                                                href="{{ route('account.index') }}"> <span
                                                    class="me-2 text-body align-bottom" data-feather="user-plus"></span>Add
                                                another account</a></li>
                                    </ul>
                                    <hr />
                                    <div class="px-3">
                                        <a class="btn btn-phoenix-secondary d-flex flex-center w-100"
                                            href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <span class="me-2" data-feather="log-out"></span>Sign out
                                        </a>
                                    </div>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                    <div class="my-2 text-center fw-bold fs-10 text-body-quaternary"><a
                                            class="text-body-quaternary me-1" href="#!">Privacy policy</a>&bull;<a
                                            class="text-body-quaternary mx-1" href="#!">Terms</a>&bull;<a
                                            class="text-body-quaternary ms-1" href="#!">Cookies</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-primary">
                            Daftar
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
        <div class="col-12 col-md-6">
            <div class="search-box ecommerce-search-box w-100">
                <form class="position-relative" action="{{ route('barangs') }}" method="get">
                    <input class="form-control search-input search form-control-sm" type="search"
                        placeholder="Search" aria-label="Search" name="search" />
                    <span class="fas fa-search search-box-icon"></span>

                </form>
            </div>
        </div>
    </div>
</nav>
