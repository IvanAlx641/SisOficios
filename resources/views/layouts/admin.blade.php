<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="horizontal">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="shortcut icon" type="image/png" href="{{ asset('materialpro/assets/images/logos/favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('materialpro/assets/css/styles.css') }}" />
    <link href="{{ asset('css/morena-theme.css') }}" rel="stylesheet">

    <title>SisSoporteTecnico</title>
</head>

<body>
    <div class="preloader">
        <img src="{{ asset('materialpro/assets/images/logos/logo-icon.svg') }}" alt="loader" class="lds-ripple img-fluid" />
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
                                        <b class="logo-icon">
                                            <img src="{{ asset('materialpro/assets/images/logos/logo-light-icon.svg') }}" alt="homepage" class="dark-logo" />
                                            <img src="{{ asset('materialpro/assets/images/logos/logo-light-icon.svg') }}" alt="homepage" class="light-logo" />
                                        </b>
                                        <span class="logo-text">
                                            <img src="{{ asset('materialpro/assets/images/logos/logo-light-text.svg') }}" alt="homepage" class="dark-logo ps-2" />
                                            <img src="{{ asset('materialpro/assets/images/logos/logo-light-text.svg') }}" class="light-logo ps-2" alt="homepage" />
                                        </span>
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
                                            <b class="logo-icon">
                                                <img src="{{ asset('materialpro/assets/images/logos/logo-light-icon.svg') }}" alt="homepage" class="dark-logo" />
                                                <img src="{{ asset('materialpro/assets/images/logos/logo-light-icon.svg') }}" alt="homepage" class="light-logo" />
                                            </b>
                                            <span class="logo-text">
                                                <img src="{{ asset('materialpro/assets/images/logos/logo-light-text.svg') }}" alt="homepage" class="dark-logo ps-2" />
                                                <img src="{{ asset('materialpro/assets/images/logos/logo-light-text.svg') }}" class="light-logo ps-2" alt="homepage" />
                                            </span>
                                        </a>
                                    </div>
                                </li>

                                <li class="nav-item d-none d-lg-block search-box">
                                    <a class="nav-link nav-icon-hover d-none d-md-flex waves-effect waves-dark" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                                    </a>
                                </li>

                                <li class="nav-item hover-dd d-none d-lg-block dropdown">
                                    <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" aria-haspopup="true" aria-expanded="false">
                                        <iconify-icon icon="solar:widget-3-line-duotone"></iconify-icon>
                                    </a>
                                    </li>
                            </ul>

                            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                                <div class="d-flex align-items-center justify-content-between">
                                    <ul class="navbar-nav gap-2 flex-row ms-auto align-items-center justify-content-center">
                                        
                                        <li class="nav-item hover-dd dropdown nav-icon-hover-bg rounded-circle">
                                            <a class="nav-link" href="javascript:void(0)" id="drop2" aria-expanded="false">
                                                <img src="{{ asset('materialpro/assets/images/svgs/icon-flag-en.svg') }}" alt="" width="20px" height="20px" class="round-20" />
                                            </a>
                                        </li>

                                        <li class="nav-item nav-icon-hover-bg rounded-circle">
                                            <a class="nav-link nav-icon-hover moon dark-layout" href="javascript:void(0)">
                                                <iconify-icon icon="solar:moon-line-duotone" class="moon"></iconify-icon>
                                            </a>
                                            <a class="nav-link nav-icon-hover sun light-layout" href="javascript:void(0)">
                                                <iconify-icon icon="solar:sun-2-line-duotone" class="sun"></iconify-icon>
                                            </a>
                                        </li>

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
                                                <img src="{{ asset('materialpro/assets/images/profile/user-1.jpg') }}" alt="user" width="30" class="profile-pic rounded-circle" />
                                            </a>
                                            <div class="dropdown-menu pt-0 content-dd overflow-hidden pt-0 dropdown-menu-end user-dd" aria-labelledby="drop2">
                                                <div class="profile-dropdown position-relative" data-simplebar>
                                                    <div class="py-3 border-bottom">
                                                        <div class="d-flex align-items-center px-3">
                                                            <img src="{{ asset('materialpro/assets/images/profile/user-1.jpg') }}" class="rounded-circle round-50" alt="" />
                                                            <div class="ms-3">
                                                                <h5 class="mb-1 fs-4">Ivan Negrete</h5>
                                                                <p class="mb-0 fs-2 d-flex align-items-center text-muted">ivan76321@gmail.com</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="message-body pb-3">
                                                        <div class="px-3 pt-3">
                                                            <div class="h6 mb-0 dropdown-item py-8 px-3 rounded-2 link">
                                                                <a href="#" class="d-flex align-items-center">Mi Perfil</a>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="px-3">
                                                            <div class="h6 mb-0 dropdown-item py-8 px-3 rounded-2 link">
                                                                <a href="#" class="d-flex align-items-center">Cerrar Sesión</a>
                                                            </div>
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
                                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                    <iconify-icon icon="solar:screencast-2-linear" class="aside-icon"></iconify-icon>
                                    <span class="hide-menu">Dashboard</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a href="/" class="sidebar-link">
                                            <i class="ti ti-circle"></i>
                                            <span class="hide-menu">Analítica</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                    <iconify-icon icon="solar:widget-3-line-duotone" class="aside-icon"></iconify-icon>
                                    <span class="hide-menu">Apps</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">
                                            <i class="ti ti-circle"></i>
                                            <span class="hide-menu">Chat</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">
                                            <i class="ti ti-circle"></i>
                                            <span class="hide-menu">Calendario</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                    <iconify-icon icon="solar:file-text-linear" class="aside-icon"></iconify-icon>
                                    <span class="hide-menu">Páginas</span>
                                </a>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                    <iconify-icon icon="solar:palette-linear" class="aside-icon"></iconify-icon>
                                    <span class="hide-menu">UI</span>
                                </a>
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Función para matar el espacio
            function fixLayoutPadding() {
                // Seleccionamos los elementos conflictivos
                var bodyWrapper = document.querySelector('.body-wrapper');
                var pageWrapper = document.querySelector('.page-wrapper');
                
                // Forzamos padding 0 con la máxima prioridad posible
                if(bodyWrapper) {
                    bodyWrapper.style.paddingTop = '0px';
                    bodyWrapper.style.marginTop = '0px';
                }
                if(pageWrapper) {
                    pageWrapper.style.paddingTop = '0px';
                }
            }

            // 1. Ejecutar inmediatamente
            fixLayoutPadding();

            // 2. Ejecutar medio segundo después (por si el script del tema tarda en cargar)
            setTimeout(fixLayoutPadding, 100);
            setTimeout(fixLayoutPadding, 500);
            setTimeout(fixLayoutPadding, 1000);

            // 3. Ejecutar cada vez que se cambie el tamaño de la ventana (para cuando rotan pantallas)
            window.addEventListener('resize', fixLayoutPadding);
        });
    </script>
</body>
</html>