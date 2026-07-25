<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(90deg, #0d47a1, #1976d2);">
    <div class="container-fluid">
        {{-- Brand with logo --}}
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <img src="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎖️</text></svg>"
                alt="Logo" width="50" class="me-2 rounded-circle">
            <span class="fw-bold">Certificate Generator</span>
        </a>

        {{-- Toggler --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
            aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Collapsible content --}}
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">
                        <i class="fa fa-gauge-high me-1"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('certificates.index') ? 'active' : '' }}"
                        href="{{ route('certificates.index') }}">
                        <i class="fa fa-table me-1"></i> All Certificates
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('bulk-exports.index') ? 'active' : '' }}"
                        href="{{ route('bulk-exports.index') }}">
                        <i class="fa fa-download me-1"></i> Bulk PDF Export
                    </a>
                </li>

            </ul>

            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
