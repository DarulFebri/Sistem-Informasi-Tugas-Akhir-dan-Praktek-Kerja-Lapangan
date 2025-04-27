<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SITA PKL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            padding-top: 56px;
        }
        .sidebar {
            height: 100vh;
            position: fixed;
            width: 250px;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            @auth
                <a class="nav-link" href="{{ 
                auth()->user()->isAdmin() ? route('admin.dashboard') : 
                (auth()->user()->isDosen() ? route('dosen.dashboard') : 
                route('mahasiswa.dashboard')) 
                }}">
                    Dashboard
                </a>
            @endauth
                SITA PKL
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{
                                auth()->user()->isAdmin() ? route('admin.dashboard') :
                                (auth()->user()->isDosen() ? route('dosen.dashboard') :
                                route('mahasiswa.dashboard'))
                            }}">
                                Dashboard
                            </a>
                        </li>
                    @endauth
                </ul>
                <div class="d-flex">
                    @auth
                        <span class="navbar-text me-3">
                            Halo, {{ Auth::user()->name }}
                        </span>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-light">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    @auth
        <div class="sidebar bg-light border-end">
            <div class="p-3">
                <h5>Menu</h5>
                <hr>
                <ul class="nav nav-pills flex-column">
                    @if(Auth::user()->isAdmin())
                        @include('partials.admin-menu')
                    @elseif(Auth::user()->isDosen())
                        @include('partials.dosen-menu')
                    @else
                        @include('partials.mahasiswa-menu')
                    @endif
                </ul>
            </div>
        </div>
    @endauth

    <main class="@auth main-content @endauth">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>