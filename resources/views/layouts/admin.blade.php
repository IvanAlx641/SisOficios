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

        <div class="page-wrapper">
            
            <header class="topbar rounded-0 border-0 bg-morena-primary">
                <nav class="navbar navbar-expand-lg container-fluid px-2 py-0 h-100 d-flex justify-content-between align-items-center" style="min-height: 70px;">
                    
                    <div class="d-flex align-items-center">
                        
                        <a href="/" class="text-nowrap logo-img d-flex align-items-center p-0">
                            <img src="{{ asset('materialpro/assets/images/morenaicons/Logolayoyt.png') }}" 
                                alt="logo" 
                                class="logo-custom" 
                                style="max-height: 45px; width: auto;" />
                        </a>

                        <span class="h4 mb-0 text-white fw-bold ms-2 text-nowrap">
                            Sistema de Oficios
                        </span>

                        <a href="/" class="d-flex align-items-center text-white ms-3 text-decoration-none" title="Ir al Inicio">
                            <iconify-icon icon="solar:home-2-bold" class="fs-4"></iconify-icon>
                        </a>
                    </div>

                    <ul class="navbar-nav flex-row align-items-center">
                        <li class="nav-item hover-dd dropdown">
                            <a class="nav-link nav-icon-hover d-flex align-items-center p-0" href="javascript:void(0)" id="drop2" aria-expanded="false">
                                
                                <span class="text-white fw-bold me-3 text-nowrap d-none d-sm-block">
                                    {{ Auth::user()->nombre ?? 'Usuario' }}
                                </span>

                                <div class="bg-white text-primary user-initial-circle shadow-sm">
                                    {{ substr(Auth::user()->nombre ?? 'U', 0, 1) }}
                                </div>
                            </a>

                            <div class="dropdown-menu content-dd overflow-hidden pt-0 dropdown-menu-end user-dd" aria-labelledby="drop2">
                                <div class="profile-dropdown position-relative" data-simplebar>
                                    <div class="py-3 border-bottom">
                                        <div class="d-flex align-items-center px-3">
                                            <div class="bg-primary-subtle text-primary rounded-circle round-50 d-flex align-items-center justify-content-center fw-bold fs-5">
                                                {{ substr(Auth::user()->nombre ?? 'U', 0, 1) }}
                                            </div>
                                            <div class="ms-3">
                                                <h5 class="mb-1 fs-4 text-dark">{{ Auth::user()->nombre ?? 'Usuario' }}</h5>
                                                <p class="mb-0 fs-2 d-flex align-items-center text-muted">{{ Auth::user()->email ?? 'email' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="message-body pb-3">
                                        <div class="px-3 pt-3">
                                            <form action="{{ route('logout') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2">
                                                    <i class="ti ti-logout"></i> Cerrar Sesión
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </nav>
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