<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Booking Kendaraan') — Nikel Fleet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-w: 240px;
            --sidebar-collapsed: 70px;
        }

        body {
            background: #f5f6fa;
            font-size: 14px;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: #1e3a5f;
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .brand small,
        .sidebar.collapsed .nav-label {
            display: none;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
        }

        .sidebar .brand {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, .1);
        }

        .sidebar .brand h6 {
            color: #fff;
            margin: 0;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .5px;
        }

        .sidebar .brand small {
            color: rgba(255, 255, 255, .5);
            font-size: 11px;
        }

        .sidebar .nav-label {
            font-size: 10px;
            color: rgba(255, 255, 255, .4);
            padding: .75rem 1rem .25rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, .7);
            padding: .5rem 1rem;
            border-radius: 6px;
            margin: 1px 8px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, .12);
            color: #fff;
        }

        .sidebar .nav-link i {
            width: 18px;
            text-align: center;
        }

        /* Sidebar hidden */
        .sidebar.hide {
            transform: translateX(-100%);
            transition: all 0.3s ease;
        }

        /* Main full */
        /* .main.full {
            margin-left: 0 !important;
        } */


        .main {
            margin-left: 0;
            /* margin-left: var(--sidebar-w); */
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        @media (min-width: 769px) {
            .main {
                margin-left: var(--sidebar-w);
            }

            .sidebar.collapsed~.main {
                margin-left: var(--sidebar-collapsed);
            }
        }

        /* MOBILE */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                pointer-events: none;

            }

            .sidebar.show {
                transform: translateX(0);
                pointer-events: auto;
            }

            .main {
                margin-left: 0;
            }
        }

        /* ===== OVERLAY ===== */
        #overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.3);
            display: none;
            z-index: 900;
        }

        #overlay.show {
            display: block;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            padding: .75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .content {
            padding: 1.5rem;
        }

        .badge-pending_l1,
        .badge-pending_l2 {
            background: #fff3cd;
            color: #664d03;
        }

        .badge-approved {
            background: #d1e7dd;
            color: #0a3622;
        }

        .badge-rejected,
        .badge-cancelled {
            background: #f8d7da;
            color: #58151c;
        }

        .badge-draft {
            background: #e2e3e5;
            color: #41464b;
        }

        @yield('extra-css')
    </style>
</head>

<body>

    {{-- Sidebar --}}
    <nav class="sidebar">
        <div class="brand">
            <h6><i class="bi bi-truck me-1"></i> Nikel Fleet</h6>
            <small>Vehicle Management</small>
        </div>

        <div class="mt-2">
            <div class="nav-label">Menu</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
            </a>
            <a href="{{ route('bookings.index') }}"
                class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i> <span>Pemesanan</span>
            </a>

            @if (auth()->user()->isApprover())
                <a href="{{ route('approvals.index') }}"
                    class="nav-link {{ request()->routeIs('approvals.*') ? 'active' : '' }}">
                    <i class="bi bi-check2-circle"></i> <span>Persetujuan</span>
                    @php $pending = auth()->user()->pendingApprovals()->count() @endphp
                    @if ($pending > 0)
                        <span class="badge bg-danger ms-auto" style="font-size:10px">{{ $pending }}</span>
                    @endif
                </a>
            @endif

            @if (auth()->user()->isAdmin())
                <div class="nav-label">Master Data</div>
                <a href="{{ route('vehicles.index') }}"
                    class="nav-link {{ request()->routeIs('vehicles.*') ? 'active' : '' }}">
                    <i class="bi bi-truck-front"></i> <span>Kendaraan</span>
                </a>
                <a href="{{ route('drivers.index') }}"
                    class="nav-link {{ request()->routeIs('drivers.*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge"></i> <span>Driver</span>
                </a>
                <a href="{{ route('regions.index') }}"
                    class="nav-link {{ request()->routeIs('regions.*') ? 'active' : '' }}">
                    <i class="bi bi-geo-alt"></i> <span>Region</span>
                </a>
                <a href="{{ route('users.index') }}"
                    class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> <span>User</span>
                </a>
                <div class="nav-label">Laporan</div>
                <a href="{{ route('reports.index') }}"
                    class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-excel"></i> <span>Laporan</span>
                </a>
            @endif
        </div>

        <div
            style="position:absolute;bottom:0;width:100%;padding:.75rem 1rem;border-top:1px solid rgba(255,255,255,.1)">
            <div style="color:rgba(255,255,255,.6);font-size:12px;margin-bottom:.5rem">
                <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
                <span class="badge bg-secondary ms-1" style="font-size:10px">{{ auth()->user()->role }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light w-100" style="font-size:12px">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </button>
            </form>
        </div>
    </nav>
    <div id="overlay"></div>

    {{-- Main --}}
    <div class="main">
        <div class="topbar">
            <div class="d-flex align-items-center gap-2">
                <button id="toggleSidebar" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-list"></i>
                </button>
                <h6 class="mb-0 fw-semibold">@yield('page-title', 'Dashboard')</h6>
            </div>
            <small class="text-muted">{{ now()->isoFormat('dddd, D MMMM Y') }}</small>
        </div>

        <div class="content">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
                    <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                    <i class="bi bi-exclamation-circle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const btn = document.getElementById('toggleSidebar');
        const sidebar = document.querySelector('.sidebar');
        const main = document.querySelector('.main');
        const overlay = document.getElementById('overlay');

        btn.addEventListener('click', () => {

            if (window.innerWidth <= 768) {
                // reset desktop state
                sidebar.classList.remove('collapsed');

                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
                return;
            }

            // reset mobile state
            sidebar.classList.remove('show');
            overlay.classList.remove('show');

            sidebar.classList.toggle('collapsed');
        });

        // klik overlay → tutup sidebar
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });

        // reset saat resize (hindari bug)
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }
        });
    </script>
    @yield('scripts')
</body>

</html>
