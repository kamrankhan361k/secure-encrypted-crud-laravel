<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Secure Encrypted CRUD')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .encryption-badge {
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .security-low { border-left: 4px solid #28a745; }
        .security-medium { border-left: 4px solid #ffc107; }
        .security-high { border-left: 4px solid #fd7e14; }
        .security-critical { border-left: 4px solid #dc3545; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('secure-data.index') }}">
                <i class="fas fa-shield-alt"></i> Secure CRUD
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('secure-data.index') }}">
                            <i class="fas fa-database"></i> All Records
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('secure-data.create') }}">
                            <i class="fas fa-plus-circle"></i> Create New
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <footer class="bg-dark text-light mt-5 py-4">
        <div class="container text-center">
            <p class="mb-0">
                <i class="fas fa-shield-alt"></i>
                Secure Encrypted CRUD System with AES-256 Encryption
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
