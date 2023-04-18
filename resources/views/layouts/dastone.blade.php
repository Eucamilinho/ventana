<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>@yield('title') | BASE</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('imagenes/favicon.ico') }}">
        <!-- App css -->
        <link href="{{asset('dastone-v2.0/HTML/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('dastone-v2.0/HTML/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('dastone-v2.0/HTML/assets/css/metisMenu.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('dastone-v2.0/plugins/daterangepicker/daterangepicker.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('dastone-v2.0/HTML/assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- DataTables -->
        <link href="{{asset('dastone-v2.0/plugins/datatables/dataTables.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('dastone-v2.0/plugins/datatables/buttons.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- Responsive datatable examples -->
        <link href="{{asset('dastone-v2.0/plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('dastone-v2.0/plugins/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('dastone-v2.0/plugins/leaflet/leaflet.css')}}" rel="stylesheet">
        <link href="{{asset('dastone-v2.0/plugins/lightpick/lightpick.css')}}" rel="stylesheet" />
        <link href="{{asset('dastone-v2.0/plugins/lightGallery/lightGallery.min.css')}}" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightslider/1.1.6/css/lightslider.min.css" integrity="sha512-yJHCxhu8pTR7P2UgXFrHvLMniOAL5ET1f5Cj+/dzl+JIlGTh5Cz+IeklcXzMavKvXP8vXqKMQyZjscjf3ZDfGA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <style>
            .close {
                background-color: white !important;
                color: white !important;
            }
            .select2 {
                width: 100% !important;
            }
            .select2-selection,.select2-container{
                height: 33px !important ;

            }
            .select2-container{
                border: 1px solid #e3ebf6 !important;
            }
            .select2-container > span{
                border: transparent !important;
            }
            .select2-container--default .select2-selection--single {
                border-radius:0px !important;
                border: 1px solid #e3ebf6;
            }
            .nav-link{
                cursor:pointer;
            }
            .btn-secondary {
                color: black !important;
                background-color:transparent !important;
                border-color: black !important;
            }
            /* select2-selection */
        </style>
        @yield('css')
    </head>
    <body class="dark-sidenav ">
        <!-- Left Sidenav -->
        {{-- @if ( Auth::user()->id == '1' ) --}}
        <div class="left-sidenav">
            <!-- LOGO -->
            <div class="brand">
                <a href="home" class="logo">
                    <span>
                        @php
                        $ImagenEmpresa= Auth::user()->imgLogoEmpresa;
                        // @if ( $ImagenEmpresa == "")

                        // @endif

                      // echo  $ImagenUser;
                       @endphp
                        <img src="{{asset($ImagenEmpresa)}}" alt="logo-small" class="logo-sm mt-4"  style="height: 60px !important;
                        width: 50% !important;">
                    </span>
                    <span>
                    </span>
                </a>
            </div>
            <!--end logo-->
            <div class="menu-content h-100" data-simplebar>
                <ul class="metismenu left-sidenav-menu">
                    <li class="menu-label mt-0">&nbsp; </li>
                    <li class="nav-item"><a class="nav-link" href="home"><i class="fas fa-home"></i>Dashboard</a></li>
                    {{-- <li class="nav-item"><a class="nav-link" href="reportes"><i class="fa fa-file-invoice"></i>Novedades</a></li> --}}
                    @if ( Auth::user()->roles()->get()[0]['pivot']["role_id"] == '1' )
                    {{-- <li class="nav-item"><a class="nav-link" href="tecnicos"><i class="fa fa-wrench"></i>Técnicos</a></li>
                    <li class="nav-item"><a class="nav-link" href="clientes"><i class="fa fa-user-tie"></i>Clientes</a></li> --}}
                    {{-- <li class="nav-item"><a class="nav-link" href="ups"><i class="fas fa-server"></i>Ups</a></li> --}}
                    <li>
                        <a href="javascript: void(0);"><i class="fas fa-wrench" class="align-self-center menu-icon"></i> Configuración  <span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li class="nav-item"><a class="nav-link" href="basicos"><i class="fas fa-building"></i>Básicos</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript: void(0);"><i class="fa fa-users" class="align-self-center menu-icon"></i> Usuarios  <span class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="nav-second-level" aria-expanded="false">
                                <li class="nav-item"><a class="nav-link" href="usuarios"><i class="fas fa-user"></i>Usuarios</a></li>
                                <li class="nav-item"><a class="nav-link" href="roles"><i class="fas fa-address-book"></i>Roles</a></li>
                            </ul>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
        {{-- @endif --}}
        <!-- end left-sidenav-->
        <div class="page-wrapper">
            <!-- Top Bar Start -->
            <div class="topbar">
                <!-- Navbar -->
                <nav class="navbar-custom">
                    <ul class="list-unstyled topbar-nav float-end mb-0">
                        <li class="dropdown notification-list">
                            <span class="nav-link dropdown-toggle arrow-none waves-light waves-effect ms-1 nav-user-name hidden-sm">{{ Auth::user()->name }}</span>
                            {{-- <a class="nav-link dropdown-toggle arrow-none waves-light waves-effect" data-bs-toggle="dropdown" href="#" role="button"
                                aria-haspopup="false" aria-expanded="false">
                                <i data-feather="bell" class="align-self-center topbar-icon"></i>
                                <span class="badge bg-danger rounded-pill noti-icon-badge" id="txt_legth_notificacion"></span>
                            </a> --}}
                            {{-- <div class="dropdown-menu dropdown-menu-end dropdown-lg pt-0">
                                <h6 class="dropdown-item-text font-15 m-0 py-3 border-bottom d-flex justify-content-between align-items-center">
                                    Notificaciones <span class="badge bg-primary rounded-pill" id="txt_legth_notificacion_2"></span>
                                </h6>
                                <div class="notification-menu" data-simplebar>
                                </div>
                            </div>  --}}
                        </li>
                    <!-- Authentication Links -->
                @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-bs-toggle="dropdown" href="#" role="button"
                                aria-haspopup="false" aria-expanded="false">
                                {{-- <span class="ms-1 nav-user-name hidden-sm">{{ Auth::user()->name }}</span> --}}


                                {{-- <img src="{{asset('dastone-v2.0/HTML/assets/images/users/user-5.jpg')}}" alt="profile-user" class="rounded-circle thumb-xs" />                                  --}}
                                @php
                                  $ImagenUser= Auth::user()->imgUser;

                                // echo  $ImagenUser;
                                 @endphp
                                 <img src="{{$ImagenUser}}" alt="profile-user" class="rounded-circle thumb-xs" style="height: 40px !important;
                                 width: 40px !important;" />

                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <div class="dropdown-divider mb-0"></div>
                                {{-- <a class="dropdown-item"  href="{{route('perfil')}}" ><i data-feather="settings" class="align-self-center icon-xs icon-dual me-1"></i> Perfil</a> --}}
                                <a class="dropdown-item" href="{{route('perfil')}}"><i data-feather="settings" class="align-self-center icon-xs icon-dual me-1"></i> Perfil</a>
                                <div class="dropdown-divider mb-0"></div>
                                <a class="dropdown-item"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();" href="{{ route('logout') }}" ><i data-feather="power" class="align-self-center icon-xs icon-dual me-1" ></i> Cerrar Sesión</a>
                            </div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                            </form>
                        </li>
                        @endguest
                        </li>
                    </ul><!--end topbar-nav-->
                    {{-- <button class="nav-link button-menu-mobile" onclick="window.location.href='/opciones'">
                        <i class="fas fa-bars"></i>
                    </button> --}}
                    <ul class="list-unstyled topbar-nav mb-0">
                        <li>
                            <button class="nav-link button-menu-mobile" >
                                <i data-feather="menu" class="align-self-center topbar-icon"></i>
                            </button>
                        </li>
                    </ul>
                </nav>
                <!-- end navbar-->
            </div>
            <!-- Top Bar End -->
            <!-- Page Content-->
            <div class="page-content">
                <div class="container-fluid">
                    <!-- Page-Title -->
                    <div class="row ">
                        <div class="col-sm-12">
                            <div class="page-title-box x_title" >
                                <div class="row">
                                    <div class="col">
                                    <h4 class="x_title"> @yield('content_header')</h4>
                                    </div>
                                    <div class="col-auto align-self-center">
                                        <h4 class="x_title" id="txt_fecha_original_despacho"></h4>
                                        <a href="#" class="btn btn-sm btn-outline-primary" id="btn_fecha_mostrar_pro"  style="display: none">
                                            <span class="day-name" id="Day_Name">Fecha:</span>&nbsp;
                                            <span class="" id="txt_fecha_original_despacho_pro"></span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar align-self-center icon-xs ms-1"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                        </a>
                                        <!-- <a href="#" class="btn btn-sm btn-outline-primary" id="Dash_Date">
                                            <span class="day-name" id="Day_Name">Today:</span>&nbsp;
                                            <span class="" id="Select_date">Jan 11</span>
                                            <i data-feather="calendar" class="align-self-center icon-xs ms-1"></i>
                                        </a>                                       -->
                                    </div><!--end col-->
                                </div><!--end row-->
                            </div><!--end page-title-box-->
                            @yield('content')
                        </div><!--end col-->
                    </div><!--end row-->
                    <!-- end page title end breadcrumb -->
                </div><!-- container -->
                <footer class="footer text-center text-sm-start">
                    &copy; <script>
                        document.write(new Date().getFullYear())
                    </script> Sodeker <span class="text-muted d-none d-sm-inline-block float-end">Desarrollado  <i
                            class="mdi mdi-heart text-danger"></i> por Sodeker</span>
                </footer><!--end footer-->
            </div>
            <!-- end page content -->
        </div>
        <!-- end page-wrapper -->
        <!-- jQuery  -->
        <script src="{{asset('dastone-v2.0/HTML/assets/js/jquery.min.js')}}"></script>
        <script src="{{asset('dastone-v2.0/HTML/assets/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('dastone-v2.0/HTML/assets/js/metismenu.min.js')}}"></script>
        <script src="{{asset('dastone-v2.0/HTML/assets/js/waves.js')}}"></script>
        <script src="{{asset('dastone-v2.0/HTML/assets/js/feather.min.js')}}"></script>
        <script src="{{asset('dastone-v2.0/HTML/assets/js/simplebar.min.js')}}"></script>
        <script src="{{asset('dastone-v2.0/HTML/assets/js/moment.js')}}"></script>
        <script src="{{asset('dastone-v2.0/plugins/daterangepicker/daterangepicker.js')}}"></script>
        <!-- Required datatable js -->
        <script src="{{asset('dastone-v2.0/plugins/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('dastone-v2.0/plugins/datatables/dataTables.bootstrap5.min.js')}}"></script>
        <!-- Buttons examples -->
        <script src="{{asset('dastone-v2.0/plugins/datatables/dataTables.buttons.min.js')}}"></script>
        <script src="{{asset('dastone-v2.0/plugins/datatables/buttons.bootstrap5.min.js')}}"></script>
        <script src="{{asset('dastone-v2.0/plugins/datatables/jszip.min.js')}}"></script>
        <script src="{{asset('dastone-v2.0/plugins/datatables/pdfmake.min.js')}}"></script>
        <script src="{{asset('dastone-v2.0/plugins/datatables/vfs_fonts.js')}}"></script>
        <script src="{{asset('dastone-v2.0/plugins/datatables/buttons.html5.min.js')}}"></script>
        <script src="{{asset('dastone-v2.0/plugins/datatables/buttons.print.min.js')}}"></script>
        <script src="{{asset('dastone-v2.0/plugins/datatables/buttons.colVis.min.js')}}"></script>
        <!-- Responsive examples -->
        <script src="{{asset('dastone-v2.0/plugins/datatables/dataTables.responsive.min.js')}}"></script>
        <script src="{{asset('dastone-v2.0/plugins/datatables/responsive.bootstrap4.min.js')}}"></script>
        <script src="{{asset('dastone-v2.0/HTML/assets/pages/jquery.datatable.init.js')}}"></script>
        <script src="{{asset('dastone-v2.0/plugins/select2/select2.min.js')}}"></script>
        <script src="{{asset('dastone-v2.0/plugins/tippy/tippy.all.min.js')}}"></script>
        <!-- App js -->
        <script src="{{asset('dastone-v2.0/HTML/assets/js/app.js')}}"></script>
        <!-- <script src="{{asset('js/vue.min.js')}}"></script> -->
        <script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"  ></script>
        <script src="{{asset('js/jquery.masknumber.js')}}"></script>
        {{-- <script src="{{asset('dastone-v2.0/plugins/leaflet/leaflet.js')}}"></script>  --}}
        <script src="{{asset('dastone-v2.0/plugins/lightpick/lightpick.js')}}"></script>
        {{-- <script src="{{asset('dastone-v2.0/HTML/assets/pages/jquery.profile.init.js')}}"></script> --}}
        <script src="{{asset('dastone-v2.0/plugins/apex-charts/apexcharts.min.js')}}"></script>
        <script src="{{asset('dastone-v2.0/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js')}}"></script>
        <script src="{{asset('dastone-v2.0/plugins/jvectormap/jquery-jvectormap-us-aea-en.js')}}"></script>
        <script src="{{asset('dastone-v2.0/plugins/lightGallery/lightgallery-all.min.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/lightslider/1.1.6/js/lightslider.min.js" integrity="sha512-Gfrxsz93rxFuB7KSYlln3wFqBaXUc1jtt3dGCp+2jTb563qYvnUBM/GP2ZUtRC27STN/zUamFtVFAIsRFoT6/w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        {{-- <script src="assets/pages/jquery.analytics_dashboard.init.js"></script> --}}
        @yield('js')
        <script>
            //activa los popup  con estilos
            tippy('.tippy-btn');
            //formato numero para las cajas de texto
            $(document).ready(function () {
                $('.maskDecimal').maskNumber();
                $('.maskInteger').maskNumber({integer: true});
            });
        </script>
    </body>
</html>
