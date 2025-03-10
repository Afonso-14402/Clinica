<!doctype html>
    <html lang="pt">
    <head>
        <meta charset="utf-8" />
        <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
        
        <meta name="description" content="" />
        @vite(['resources/css/app.css'])
        
        <!-- Fonts -->
        
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />


    <link rel="stylesheet" href="https://cdn.dhtmlx.com/scheduler/edge/dhtmlxscheduler.css">



       
<style>
    :root {
        --primary-color: #2563eb;
        --primary-dark: #1e40af;
        --primary-light: #60a5fa;
        --text-light: #ffffff;
        --text-muted: #cbd5e1;
        --bg-dark: #0f172a;
    }

    .sidebar {
        background: var(--bg-dark);
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
    }

    #container {
        background: var(--bg-dark) !important;
        border-right: 1px solid rgba(255, 255, 255, 0.07);
    }

    .app-brand-text {
        color: #ffffff !important;
        font-weight: 800;
        font-size: 2.2rem;
        letter-spacing: -0.5px;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        text-transform: none;
        font-family: 'Public Sans', sans-serif;
    }

    .app-brand-link {
        text-decoration: none !important;
        padding: 0 1.5rem;
        margin-bottom: 1rem;
    }

    .app-brand-link:hover .app-brand-text {
        color: var(--primary-light) !important;
        transition: all 0.3s ease;
    }

    .menu-inner > .menu-item {
        margin: 8px 12px;
    }

    .menu-link {
        color: var(--text-light) !important;
        border-radius: 8px;
        margin: 4px 8px;
        transition: all 0.3s ease;
        font-weight: 500;
        font-size: 1rem;
        padding: 12px 16px;
    }

    .menu-link:hover, 
    .menu-link:focus {
        background: rgba(59, 130, 246, 0.15) !important;
        color: var(--primary-light) !important;
        transform: translateX(4px);
    }

    .menu-link.active {
        background: var(--primary-color) !important;
        color: var(--text-light) !important;
        font-weight: 600;
    }

    .menu-icon {
        color: var(--text-light);
        font-size: 1.2rem;
        margin-right: 12px;
    }

    .menu-link:hover .menu-icon,
    .menu-link.active .menu-icon {
        color: var(--primary-light);
    }

    .menu-sub {
        background: rgba(15, 23, 42, 0.9);
        margin: 0 12px;
        border-radius: 8px;
        padding: 8px 0;
    }

    .menu-sub .menu-link {
        padding: 10px 16px;
        font-size: 0.95rem;
        color: var(--text-muted) !important;
        margin: 4px 8px;
    }

    .menu-sub .menu-link:hover {
        color: var(--text-light) !important;
    }

    .menu-header-text {
        color: var(--primary-light);
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin: 24px 0 12px 24px;
        opacity: 0.9;
    }

    .menu-inner-shadow {
        display: none;
    }

    /* Scrollbar personalizada */
    #container::-webkit-scrollbar {
        width: 6px;
    }

    #container::-webkit-scrollbar-track {
        background: var(--bg-dark);
    }

    #container::-webkit-scrollbar-thumb {
        background: #334155;
        border-radius: 3px;
    }

    #container::-webkit-scrollbar-thumb:hover {
        background: #475569;
    }
</style>

    </head>

    <body>
        <main class="d-flex">

            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div id="container" style="/*max-height: 1000px;*/ height:100vh; position:fixed;  overflow-y: auto; background-color: #ffffff;">
  
                <div class="app-brand demo">
                <a href="" class="app-brand-link">
                    <span class="app-brand-logo demo">
                        <!--<img class="mb-3 rounded-circle shadow-sm" src="" alt="Logo da Clínica" width="60" height="60">  -->
                        <defs>
                        <path
                            d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z"
                            id="path-1"></path>
                        <path
                            d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z"
                            id="path-3"></path>
                        <path
                            d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z"
                            id="path-4"></path>
                        <path
                            d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z"
                            id="path-5"></path>
                        </defs>
                        <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                            <g id="Icon" transform="translate(27.000000, 15.000000)">
                            <g id="Mask" transform="translate(0.000000, 8.000000)">
                                <mask id="mask-2" fill="white">
                                <use xlink:href="#path-1"></use>
                                </mask>
                                <use fill="#696cff" xlink:href="#path-1"></use>
                                <g id="Path-3" mask="url(#mask-2)">
                                <use fill="#696cff" xlink:href="#path-3"></use>
                                <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                                </g>
                                <g id="Path-4" mask="url(#mask-2)">
                                <use fill="#696cff" xlink:href="#path-4"></use>
                                <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                                </g>
                            </g>
                            <g
                                id="Triangle"
                                transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) ">
                                <use fill="#696cff" xlink:href="#path-5"></use>
                                <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                            </g>
                            </g>
                        </g>
                        </g>
                    </svg>
                    </span>
                    <a href="{{ route('home') }}">
                        <span class="app-brand-text demo menu-text fw-bold ms-2">APclínica</span>
                    </a>   
                </a>
            
                <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                    <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
                </a>
                </div>
            
                <div class="menu-inner-shadow"></div>
            
                <ul class="menu-inner py-1">
                
            
                <!-- Registar -->
                @if (isset($user) && $user->role->role != 'Patient')
                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-user-plus"></i>
                        <div class="text-truncate" data-i18n="Registar">Registar</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="{{ route('list.listpatient') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-group"></i>
                                Resgitar utentes
                            </a>
                        </li>
                        @if (isset($user) && $user->role->role != 'Patient' && $user->role->role != 'Doctor')
                        <li class="menu-item">
                            <a href="{{ route('list.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-plus-medical"></i>
                                Registar medico
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if (isset($user) && $user->role->role != 'Patient'&& $user->role->role != 'Doctor')
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-calendar-plus"></i>
                        <div class="text-truncate" data-i18n="Marcações">Marcações</div>
                    </a>
                    <ul class="menu-sub">
                        @if (isset($user) && $user->role->role != 'Patient' && $user->role->role != 'Doctor')
                        <li class="menu-item">
                            <a href="{{ route('appointments.create') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-calendar"></i>
                                <div class="text-truncate" data-i18n="Agenda/Calendário">Agenda/Calendário</div>
                            </a>
                        </li>
                        
                        <li class="menu-item">
                            <a href="{{ route('appointments.pending') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-time-five"></i>
                                <div class="text-truncate" data-i18n="Pedidos Pendentes">Pedidos Pendentes</div>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                <li class="menu-item">
                    <a href="{{ route('appointments.history') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-history"></i>
                        <div class="text-truncate" data-i18n="Histórico">Histórico de Consultas</div>
                    </a>
                </li>
                

                @if (isset($user) && $user->role->role != 'Patient' && $user->role->role != 'Doctor')
                <li class="menu-item">
                    <a href="{{ route('family.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-heart"></i>
                        <div class="text-truncate" data-i18n="Médicos">Médicos de Família</div>
                    </a>
                </li>
                @endif

                @if (isset($user) && $user->role->role != 'Patient' && $user->role->role != 'Doctor')
                <li class="menu-item">
                    <a href="{{ route('admin.list') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-cog"></i>
                        <div class="text-truncate" data-i18n="Admin">Contas</div>
                    </a>
                </li>
                @endif

                </ul>
            </div>
            </aside>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="main-content">
                            @yield('content')
                            
                        </div>
                    </div>
                </div>
            </div>
    </main>

    <!-- JS -->
<script src="https://cdn.dhtmlx.com/scheduler/edge/dhtmlxscheduler.js"></script>
    @vite(['resources/vendor/js/app.js', 'resources/js/app.js','resources/vendor/libs/app.js'])

    <meta name="csrf-token" content="{{ csrf_token() }}">

</body>
</html>
