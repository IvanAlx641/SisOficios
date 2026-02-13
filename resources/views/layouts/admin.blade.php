<!DOCTYPE html>
<html lang="es" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="horizontal">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="shortcut icon" type="image/png" href="{{ asset('materialpro/assets/images/morenaicons/favicon.png') }}" />
    
    <link rel="stylesheet" href="{{ asset('materialpro/assets/css/styles.css') }}" />
    <link href="{{ asset('css/morena-theme.css') }}" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        /* Ajuste del Logo PNG */
        .logo-custom {
            height: 45px; 
            width: auto;
            object-fit: contain;
        }
        
        /* Ocultar elementos no deseados */
        .moon, .sun, .lang-flag { display: none !important; }

        /* CORRECCIÓN ICONO DE USUARIO (CÍRCULO PERFECTO) */
        .user-initial-circle {
            width: 40px !important;
            height: 40px !important;
            min-width: 40px; 
            border-radius: 50% !important;
            display: flex;
            align-items: center;
            justify-content: center;
            aspect-ratio: 1 / 1; 
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        /* CORRECCIÓN BORDES/LÍNEAS EXTRA */
        .topbar, .app-header {
            border-bottom: none !important;
            box-shadow: none !important;
        }
    </style>

    <title>Sistema de Oficios</title>
</head>

<body>
    <div class="preloader">
        <img src="{{ asset('materialpro/assets/images/morenaicons/Logolayoyt.png') }}" alt="loader" class="lds-ripple img-fluid" style="width: 80px;" />
    </div>

    <div id="main-wrapper">
        <aside class="left-sidebar with-vertical">
            <div>
                <nav class="sidebar-nav scroll-sidebar" data-simplebar>
                    <ul id="sidebarnav">
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="/" aria-expanded="false">
                                <iconify-icon icon="solar:screencast-2-linear" class="aside-icon"></iconify-icon>
                                <span class="hide-menu">Inicio</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="page-wrapper">
            
        <header class="topbar rounded-0 border-0 bg-morena-primary">
            <div class="app-header border-0 shadow-none">
                <nav class="navbar navbar-expand-xl container p-0" style="font-family: inherit;">
                    
                    <div class="d-flex align-items-center justify-content-between w-100" style="height: 64px;">
                        
                        <div class="d-flex align-items-center">
                            <a href="/" class="text-nowrap logo-img d-flex align-items-center">
                                <img src="{{ asset('materialpro/assets/images/morenaicons/Logolayoyt.png') }}" alt="logo" class="logo-custom" style="height: 40px;" />
                            </a>

                            <span class="text-white fw-bold fs-4 ms-3">
                                Sistema de Oficios
                            </span>

                            <a href="/dashboard" class="text-white d-flex align-items-center ms-3" title="Dashboard">
                                <iconify-icon icon="solar:home-2-linear" width="24" height="24"></iconify-icon>
                            </a>
                        </div>

                        <div class="d-flex align-items-center">
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown">
                                    <a class="nav-link d-flex align-items-center p-0" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="d-none d-lg-block text-nowrap text-white fw-medium me-3">
                                            {{ Auth::user()->nombre ?? 'Usuario' }}
                                        </span>
                                        
                                        <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" 
                                            style="width: 40px; height: 40px; min-width: 40px; flex-shrink: 0; line-height: 0;">
                                            {{ substr(Auth::user()->nombre ?? 'U', 0, 1) }}
                                        </div>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="drop2" style="min-width: 210px;">
                                        <div class="py-3 border-bottom text-center">
                                            <h5 class="mb-1 fs-4 text-dark fw-semibold">{{ Auth::user()->nombre ?? 'Usuario' }}</h5>
                                            <p class="mb-0 fs-2 text-muted">{{ Auth::user()->email ?? 'email' }}</p>
                                        </div>
                                        <div class="p-3">
                                            <form action="{{ route('logout') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2">
                                                    <i class="ti ti-logout"></i> Cerrar Sesión
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        
                    </div>
                </nav>
            </div>
        </header>

                <aside class="left-sidebar with-horizontal">
                <div>
                    <nav id="sidebarnavh" class="sidebar-nav scroll-sidebar container-fluid">
                        <ul id="sidebarnav">
                            <li class="sidebar-item">
                                <a class="sidebar-link" href="/" aria-expanded="false">
                                    <iconify-icon icon="solar:screencast-2-linear"></iconify-icon>
                                    <span class="hide-menu">Inicio</span>
                                </a>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                    <iconify-icon icon="solar:user-id-line-duotone" class="aside-icon"></iconify-icon>
                                    <span class="hide-menu">Administracion</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    
                                    <li class="sidebar-item">
                                        <a href="{{ route('usuario.index') }}" class="sidebar-link">
                                            <i class="ti ti-user"></i>
                                            <span class="hide-menu">Usuarios</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="{{route('solicitante.index')}}" class="sidebar-link">
                                            <i class="ti ti-file-pencil"></i>
                                            <span class="hide-menu">Solicitantes</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="{{ route('tiporequerimiento.index') }}" class="sidebar-link">
                                            <i class="ti ti-checklist"></i>
                                            <span class="hide-menu">Tipos de Requerimientos</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="{{ route('sistema.index') }}" class="sidebar-link">
                                            <i class="ti ti-screen-share"></i>
                                            <span class="hide-menu">Sistemas</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                    <iconify-icon icon="solar:file-text-linear" class="aside-icon"></iconify-icon>
                                    <span class="hide-menu">Oficios</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">
                                            <i class="ti ti-file-plus"></i>
                                            <span class="hide-menu">Registro</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">
                                            <i class="ti ti-tournament"></i>
                                            <span class="hide-menu">Turno</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">
                                            <i class="ti ti-arrow-guide"></i>
                                            <span class="hide-menu">Seguimiento</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">
                                            <i class="ti ti-file-symlink"></i>
                                            <span class="hide-menu">Respuesta</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">
                                            <i class="ti ti-file-search"></i>
                                            <span class="hide-menu">Buscador</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                    <iconify-icon icon="solar:file-check-linear" class="aside-icon"></iconify-icon>
                                    <span class="hide-menu">Actividades</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">
                                            <i class="ti ti-file-like"></i>
                                            <span class="hide-menu">Bitacora</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                    <iconify-icon icon="solar:diagram-up-broken" class="aside-icon"></iconify-icon>
                                    <span class="hide-menu">Informes</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">   
                                        <a href="#" class="sidebar-link">
                                            <i class="ti ti-file-analytics"></i>
                                            <span class="hide-menu">Oficios</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">
                                            <i class="ti ti-home-stats"></i>
                                            <span class="hide-menu">Actividades</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>
            <div class="body-wrapper">
                <div class="container-fluid">
                    
                    <div class="mb-3">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 d-flex align-items-center" role="alert" 
                                style="background-color: #d1e7dd; color: #0f5132; border-left: 5px solid #198754 !important;">
                                <i class="ti ti-circle-check fs-4 me-2 text-success"></i>
                                <div><strong>¡Excelente!</strong> {{ session('success') }}</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 d-flex align-items-center" role="alert"
                                style="background-color: #f8d7da; color: #842029; border-left: 5px solid #dc3545 !important;">
                                <i class="ti ti-alert-triangle fs-4 me-2 text-danger"></i>
                                <div><strong>¡Atención!</strong> {{ session('error') }}</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        </div>
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <div class="dark-transparent sidebartoggler"></div>
    
    <script src="{{ asset('materialpro/assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('materialpro/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('materialpro/assets/libs/simplebar/dist/simplebar.min.js') }}"></script>
    
    <script src="{{ asset('materialpro/assets/js/theme/app.horizontal.init.js') }}"></script>
    
    <script src="{{ asset('materialpro/assets/js/theme/theme.js') }}"></script>
    <script src="{{ asset('materialpro/assets/js/theme/app.min.js') }}"></script>
    <script src="{{ asset('materialpro/assets/js/theme/sidebarmenu.js') }}"></script>
    <script src="{{ asset('materialpro/assets/js/theme/feather.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    
    @stack('scripts') 

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var alerts = document.querySelectorAll('.alert-success, .alert-danger');
            if(alerts) {
                setTimeout(function() {
                    alerts.forEach(function(alert) {
                        var bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    });
                }, 5000); // 5 SEGUNDOS
            }
        });
    </script>
</body>
</html>