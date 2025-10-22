<div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">

    <style>
        #sidebarnav a {
            text-decoration: none !important;
        }

        #sidebarnav a:hover,
        #sidebarnav a.active {
            text-decoration: none !important;
        }
    </style>

    <header class="topbar" data-navbarbg="skin6">
        <nav class="navbar top-navbar navbar-expand-md">
            <div class="navbar-header" data-logobg="skin6">
                <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)">
                    <i class="ti-menu ti-close"></i>
                </a>
                <div class="navbar-brand" style="display: flex; align-items: center; gap: 8px;">
                    <a href="{{ route('dashboard.index') }}"
                        style="display: flex; align-items: center; text-decoration: none;">
                        <span class="logo-icon" style="display: flex;">
                            <img src="{{ asset('assets/images/logo-icon.png') }}" alt="homepage" class="dark-logo" />
                            <img src="{{ asset('assets/images/logo-icon.png') }}" alt="homepage" class="light-logo" />
                        </span>
                        <span class="logo-text" style="display: flex;">
                            <img src="{{ asset('assets/images/logo-text.png') }}" alt="homepage" class="dark-logo" />
                            <img src="{{ asset('assets/images/logo-light-text.png') }}" alt="homepage"
                                class="light-logo" />
                        </span>
                    </a>
                </div>

                <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                    data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <i class="ti-more"></i>
                </a>
            </div>

            <div class="navbar-collapse collapse" id="navbarSupportedContent">
                <ul class="navbar-nav float-left mr-auto ml-3 pl-1">
                </ul>

                <ul class="navbar-nav float-right">
                    <li class="nav-item dropdown position-relative">
                        <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            @php
                                $profilePhotoPath = Auth::user()->profilePhoto->photo_path ?? null;
                                $photoUrl = $profilePhotoPath
                                    ? asset('storage/' . $profilePhotoPath)
                                    : asset('assets/images/users/profile-pic.jpg');
                            @endphp

                            <img src="{{ $photoUrl }}" alt="user" class="rounded-circle" width="40" />

                            <span class="ml-2 d-none d-lg-inline-block">
                                <span>Hello,</span>
                                <span
                                    class="text-dark">{{ Auth::user()->username ?? Auth::user()->name ?? 'User' }}</span>
                                <i data-feather="chevron-down" class="svg-icon"></i>
                            </span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY" style="z-index: 2000;">
                            <a class="dropdown-item" href="{{ route('profile_photo.create') }}">
                                <i data-feather="user" class="svg-icon mr-2 ml-1"></i> Add photo profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <a class="dropdown-item" href="#" id="logout-btn">
                                <i data-feather="power" class="svg-icon mr-2 ml-1"></i> Logout
                            </a>

                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <aside class="left-sidebar" data-sidebarbg="skin6">
        <div class="scroll-sidebar" data-sidebarbg="skin6">
            <nav class="sidebar-nav">
                <ul id="sidebarnav">
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('dashboard.index') }}" aria-expanded="false">
                            <i data-feather="home" class="feather-icon"></i>
                            <span class="hide-menu">Dashboard</span>
                        </a>
                    </li>

                    <li class="list-divider"></li>
                    <li class="nav-small-cap"><span class="hide-menu">Applications</span></li>

                    @php $role = auth()->user()->role; @endphp

                    @if($role === 'admin')
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('users.index') }}">
                                <i data-feather="users" class="feather-icon"></i>
                                <span class="hide-menu">User</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('reports.index') }}">
                                <i data-feather="bar-chart-2" class="feather-icon"></i>
                                <span class="hide-menu">Report</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('kategoris.index') }}">
                                <i data-feather="layers" class="feather-icon"></i>
                                <span class="hide-menu">Kategori</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('menus.index') }}">
                                <i data-feather="book-open" class="feather-icon"></i>
                                <span class="hide-menu">Menus</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('settings.kitchen.index') }}">
                                <i data-feather="settings" class="feather-icon"></i>
                                <span class="hide-menu">WA Kitchen Setting</span>
                            </a>
                        </li>
                    @elseif($role === 'kasir')
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('orders.index') }}">
                                <i data-feather="shopping-cart" class="feather-icon"></i>
                                <span class="hide-menu">Order</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('detail_orders.index') }}">
                                <i data-feather="file-text" class="feather-icon"></i>
                                <span class="hide-menu">Detail Order</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </aside>


</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const logoutBtn = document.getElementById('logout-btn');

            if (logoutBtn) {
                logoutBtn.addEventListener('click', function (e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Yakin ingin logout?',
                        text: "Sesi kamu akan diakhiri.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, logout',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('logout-form').submit();
                        }
                    });
                });
            }
        });
    </script>
@endpush