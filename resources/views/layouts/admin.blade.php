<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Reservation System') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles -->
    <style>
        body.modal-open {
            overflow: hidden;
        }

        .modal {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1055;
            display: none;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            overflow-y: auto;
            outline: 0;
        }

        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
            width: 100vw;
            height: 100vh;
            background-color: #000;
        }

        .modal.fade .modal-dialog {
            transition: transform .3s ease-out;
            transform: translate(0, -50px);
        }

        .modal.show .modal-dialog {
            transform: none;
        }

        .modal-dialog {
            position: relative;
            width: auto;
            margin: 0.5rem;
            pointer-events: none;
        }

        .modal-content {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 100%;
            pointer-events: auto;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid rgba(0,0,0,.2);
            border-radius: 0.3rem;
            outline: 0;
        }

        @media (min-width: 576px) {
            .modal-dialog {
                max-width: 500px;
                margin: 1.75rem auto;
            }

            .modal-dialog-centered {
                min-height: calc(100% - 3.5rem);
                display: flex;
                align-items: center;
            }

            .modal-dialog-lg {
                max-width: 800px;
            }
        }
    </style>
</head>
<body class="bg-light">
    @auth
        @if(auth()->user()->hasRole('admin'))
            <div class="d-flex">
                <!-- Sidebar -->
                <div class="d-none d-md-flex flex-column flex-shrink-0 p-3 bg-white shadow-sm" style="width: 280px; min-height: 100vh;">
                    <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none">
                        <span class="fs-4 text-primary fw-bold">Admin Panel</span>
                    </a>
                    <hr>
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}"
                               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-dark' }}">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}"
                               class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : 'text-dark' }}">
                                <i class="fas fa-users me-2"></i>
                                Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.services.index') }}"
                               class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : 'text-dark' }}">
                                <i class="fas fa-concierge-bell me-2"></i>
                                Services
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.reservations.index') }}"
                               class="nav-link {{ request()->routeIs('admin.reservations.*') ? 'active' : 'text-dark' }}">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Reservations
                            </a>
                        </li>
                        {{-- Commenting out unready notifications route
                        <li class="nav-item">
                            <a href="{{ route('admin.notifications.index') }}"
                               class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : 'text-dark' }}">
                                <i class="fas fa-bell me-2"></i>
                                Notifications
                            </a>
                        </li>
                        --}}
                    </ul>
                    <hr>
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}" alt="" width="32" height="32" class="rounded-circle me-2">
                            <strong>{{ auth()->user()->name }}</strong>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Sign out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Main content -->
                <div class="flex-grow-1">
                    <!-- Top navbar for mobile -->
                    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm d-md-none">
                        <div class="container-fluid">
                            <a class="navbar-brand" href="#">Admin Panel</a>
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarNav">
                                <ul class="navbar-nav">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                                           href="{{ route('admin.dashboard') }}">
                                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                                           href="{{ route('admin.users.index') }}">
                                            <i class="fas fa-users me-2"></i>Users
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}"
                                           href="{{ route('admin.services.index') }}">
                                            <i class="fas fa-concierge-bell me-2"></i>Services
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}"
                                           href="{{ route('admin.reservations.index') }}">
                                            <i class="fas fa-calendar-alt me-2"></i>Reservations
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>

                    <!-- Page content -->
                    <main class="p-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @yield('content')
                    </main>
                </div>
            </div>
        @else
            <script>window.location.href = "{{ route('home') }}";</script>
        @endif
    @else
        <script>window.location.href = "{{ route('login') }}";</script>
    @endauth

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
