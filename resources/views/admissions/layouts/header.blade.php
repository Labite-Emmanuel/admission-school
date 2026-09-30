    <!-- Header -->
    <div class="header">
        <!-- Logo -->
        <div class="header-left active">
            <!-- <a href="{{ route('home-admission') }}" class="logo logo-normal">
                <img src="{{ URL::asset('logo.jpg') }}" width="50px" class="img-fluid" alt="Logo">
            </a> -->
            <!-- <a href="{{ route('home-admission') }}" class="logo-small">
                <img src="{{ URL::asset('logo.jpg') }}" width="50px" class="img-fluid" alt="Logo">
            </a> -->
            <a href="{{ route('home-admission') }}" class="dark-logo">
                <img src="{{ URL::asset('logo.jpg') }}" width="50px" class="img-fluid" alt="Logo">
            </a>
            <a id="toggle_btn" href="javascript:void(0);">
                <i class="ti ti-menu-deep"></i>
            </a>
        </div>
        <!-- /Logo -->

        <a id="mobile_btn" class="mobile_btn" href="#sidebar">
            <span class="bar-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </a>

        <div class="header-user">
            <div class="nav user-menu">
                <div class="d-flex align-items-center ms-auto">
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                            <span class="avatar avatar-md rounded-circle bg-primary d-inline-flex align-items-center justify-content-center text-white fw-semibold">
                                {{ strtoupper(substr(Auth::user()->first_name ?? 'U', 0, 1)) }}
                            </span>
                            <span class="ms-2 d-none d-md-inline">
                                <span class="text-dark fw-normal">{{ Auth::user()->first_name ?? 'User' }} {{ Auth::user()->username ?? '' }}</span>
                                <span class="text-muted d-block small">{{ ucfirst(Auth::user()->role ?? 'Student') }}</span>
                            </span>
                            <i class="ti ti-chevron-down ms-1"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <div class="d-block">
                                <div class="d-flex align-items-center p-2">
                                    <span class="avatar avatar-md rounded-circle bg-primary me-2 d-inline-flex align-items-center justify-content-center text-white fw-semibold">
                                        {{ strtoupper(substr(Auth::user()->first_name ?? 'U', 0, 1)) }}
                                    </span>
                                    <div>
                                        <h6 class="mb-0">{{ Auth::user()->first_name ?? 'User' }} {{ Auth::user()->username ?? '' }}</h6>
                                        <p class="text-primary mb-0 small">{{ ucfirst(Auth::user()->role ?? 'Student') }}</p>
                                    </div>
                                </div>
                                <hr class="m-0">
                                <a class="dropdown-item d-inline-flex align-items-center p-2" href="{{ route('home-admission') }}">
                                    <i class="ti ti-home me-2"></i>Dashboard
                                </a>
                                <!-- <a class="dropdown-item d-inline-flex align-items-center p-2" href="{{ route('admission') }}">
                                    <i class="ti ti-list me-2"></i>Admissions
                                </a> -->
                                <hr class="m-0">
                                <a class="dropdown-item d-inline-flex align-items-center p-2" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('admission-logout-form').submit();">
                                    <i class="ti ti-logout me-2"></i>Logout
                                </a>
                                <form id="admission-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Header -->

    <!-- Mobile Menu -->
    <div class="dropdown mobile-user-menu">
        <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="ti ti-dots-vertical"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-end">
            <a class="dropdown-item" href="{{ route('home-admission') }}">Dashboard</a>
            <a class="dropdown-item" href="{{ route('admission') }}">Admissions</a>
            <a class="dropdown-item" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('admission-logout-form-mobile').submit();">Logout</a>
            <form id="admission-logout-form-mobile" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
    <!-- /Mobile Menu -->
