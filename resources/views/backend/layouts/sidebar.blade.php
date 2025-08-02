<nav class="navbar navbar-vertical navbar-expand-lg">
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <!-- scrollbar removed-->
        <div class="navbar-vertical-content">
            <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                <li class="nav-item">
                    <div class="nav-item-wrapper">
                        <a class="nav-link label-1 {{ request()->routeIs('panel.dashboard.*') ? 'active' : '' }}"
                            href="{{ route('panel.dashboard') }}" role="button" data-bs-toggle="" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span data-feather="pie-chart"></span></span>
                                <span class="nav-link-text-wrapper"><span class="nav-link-text">Dashboard</span></span>
                            </div>
                        </a>
                    </div>
                    <!-- parent pages-->
                    <p class="navbar-vertical-label">Data Master</p>

                    <div class="nav-item-wrapper"><a
                            class="nav-link label-1 {{ request()->routeIs('panel.categories.*') ? 'active' : '' }}"
                            href="{{ route('panel.categories.index') }}" role="button" data-bs-toggle=""
                            aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                        data-feather="tag"></span></span><span class="nav-link-text-wrapper"><span
                                        class="nav-link-text">Kategori</span></span>
                            </div>
                        </a>
                    </div>
                    <div class="nav-item-wrapper"><a
                            class="nav-link label-1 {{ request()->routeIs('panel.barangs.*') ? 'active' : '' }}"
                            href="{{ route('panel.barangs.index') }}" role="button" data-bs-toggle=""
                            aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                        data-feather="box"></span></span><span class="nav-link-text-wrapper"><span
                                        class="nav-link-text">Barang</span></span>
                            </div>
                        </a>
                    </div>
                    <div class="nav-item-wrapper"><a
                            class="nav-link label-1 {{ request()->routeIs('panel.renters.*') ? 'active' : '' }}"
                            href="{{ route('panel.renters.index') }}" role="button" data-bs-toggle=""
                            aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                        data-feather="users"></span></span><span class="nav-link-text-wrapper"><span
                                        class="nav-link-text">Peminjam</span></span>
                            </div>
                        </a>
                    </div>
                    <div class="nav-item-wrapper"><a
                            class="nav-link label-1 {{ request()->routeIs('panel.admins.*') ? 'active' : '' }}"
                            href="{{ route('panel.admins.index') }}" role="button" data-bs-toggle=""
                            aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                        data-feather="user"></span></span><span class="nav-link-text-wrapper"><span
                                        class="nav-link-text">Admin</span></span>
                            </div>
                        </a>
                    </div>

                    <p class="navbar-vertical-label">Peminjaman</p>

                    <div class="nav-item-wrapper"><a
                            class="nav-link label-1 {{ request()->routeIs('panel.rentings.*') ? 'active' : '' }}"
                            href="{{ route('panel.rentings.index') }}" role="button" data-bs-toggle=""
                            aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                        data-feather="server"></span></span><span class="nav-link-text-wrapper"><span
                                        class="nav-link-text">Log Peminjaman</span></span>
                            </div>
                        </a>
                    </div>
                    <div class="nav-item-wrapper"><a
                            class="nav-link label-1 {{ request()->routeIs('panel.returns.*') ? 'active' : '' }}"
                            href="{{ route('panel.returns.index') }}" role="button" data-bs-toggle=""
                            aria-expanded="false">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                        data-feather="refresh-ccw"></span></span><span
                                    class="nav-link-text-wrapper"><span class="nav-link-text">Pengembalian</span></span>
                            </div>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="navbar-vertical-footer">
        <button
            class="btn navbar-vertical-toggle border-0 fw-semibold w-100 white-space-nowrap d-flex align-items-center">
            <span class="uil uil-left-arrow-to-left fs-8"></span>
            <span class="uil uil-arrow-from-right fs-8"></span>
            <span class="navbar-vertical-footer-text ms-2">Collapsed View</span>
        </button>
    </div>
</nav>
