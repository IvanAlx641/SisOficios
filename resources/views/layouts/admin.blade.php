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
            object-fit: contain; /* Asegura que no se deforme */
        }
        
        /* Ocultar elementos no deseados */
        .moon, .sun, .lang-flag { display: none !important; }
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
            
            <header class="topbar rounded-0 border-0 bg-primary">
                    <div class="with-vertical">
                        <nav class="navbar navbar-expand-lg px-lg-0 px-3 py-0">
                            <div class="d-none d-lg-block">
                                <div class="brand-logo d-flex align-items-center justify-content-between">
                                    <a href="/" class="text-nowrap logo-img d-flex align-items-center gap-2">
                                        <img src="{{ asset('materialpro/assets/images/morenaicons/Logolayoyt.png') }}" alt="logo" class="logo-custom" />
                                    </a>
                                </div>
                            </div>
                            <ul class="navbar-nav gap-2">
                                <li class="nav-item nav-icon-hover-bg rounded-circle">
                                    <a class="nav-link nav-icon-hover sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                                        <iconify-icon icon="solar:list-bold"></iconify-icon>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <div class="app-header with-horizontal">
                        <nav class="navbar navbar-expand-xl container-fluid">
                            <ul class="navbar-nav gap-2 align-items-center">
                                <li class="nav-item d-block d-xl-none">
                                    <a class="nav-link sidebartoggler ms-n3" id="sidebarCollapse" href="javascript:void(0)">
                                        <iconify-icon icon="solar:hamburger-menu-line-duotone"></iconify-icon>
                                    </a>
                                </li>
                                
                                <li class="nav-item d-none d-xl-block">
                                    <div class="brand-logo d-flex align-items-center justify-content-between">
                                        <a href="/" class="text-nowrap logo-img d-flex align-items-center gap-2">
                                            <img src="{{ asset('materialpro/assets/images/morenaicons/Logolayoyt.png') }}" alt="logo" class="logo-custom" />
                                        </a>
                                    </div>
                                </li>
                                
                                </ul>

                            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                                <div class="d-flex align-items-center justify-content-between">
                                    <ul class="navbar-nav gap-2 flex-row ms-auto align-items-center justify-content-center">
                                        
                                        <li class="nav-item hover-dd dropdown nav-icon-hover-bg rounded-circle d-none d-lg-block">
                                            <a class="nav-link nav-icon-hover waves-effect waves-dark" href="javascript:void(0)" id="drop2" aria-expanded="false">
                                                <iconify-icon icon="solar:bell-bing-line-duotone"></iconify-icon>
                                                <div class="notify">
                                                    <span class="heartbit"></span>
                                                    <span class="point"></span>
                                                </div>
                                            </a>
                                        </li>

                                        <li class="nav-item hover-dd dropdown">
                                            <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" aria-expanded="false">
                                                <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold fs-5" 
                                                     style="width: 35px; height: 35px;">
                                                    {{ substr(Auth::user()->nombre ?? 'U', 0, 1) }}
                                                </div>
                                            </a>
                                            
                                            <div class="dropdown-menu pt-0 content-dd overflow-hidden pt-0 dropdown-menu-end user-dd" aria-labelledby="drop2">
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
                                        <a href="#" class="sidebar-link">
                                            <i class="ti ti-file-pencil"></i>
                                            <span class="hide-menu">Solicitantes</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">
                                            <i class="ti ti-checklist"></i>
                                            <span class="hide-menu">Catálogos</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">
                                            <i class="ti ti-screen-share"></i>
                                            <span class="hide-menu">Sistemas</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">
                                            <i class="ti ti-users"></i>
                                            <span class="hide-menu">Roles</span>
                                        </a>
                                    </li>

                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">
                                            <i class="ti ti-user-check"></i>
                                            <span class="hide-menu">Permisos</span>
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
</body>
</html>