<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'eNCOD') }}</title>
    <link rel="icon" href="/assets/images/logo.png" type="image/png" />

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!--Plug in-->
    <script src="/assets/js/jquery.min.js"></script>
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <link href="/assets/plugins/select2/css/select2.min.css" rel="stylesheet" />
    <link href="/assets/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="/assets/css/app.css" rel="stylesheet">
    <link href="/assets/css/icons.css" rel="stylesheet">
    <link href="/assets/plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" />
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="/assets/plugins/sweet-alert2/sweetalert2.min.js"></script>
    <script src="/assets/plugins/select2/js/select2.min.js"></script>
    <style href="/assets/css/global.css" rel="stylesheet"></style>
    <script src="/assets/js/Global.js"></script>
    <!--app JS-->
    <script src="/assets/js/app.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Hanuman&display=swap');
    </style>
    <style>
        body {
            font-family: 'Hanuman', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
        }
        .form-select{
            font-size: 14px;
            height: 38px;
        }

        .select2-container .select2-selection--single {
            height: 38px !important;
            font-size: 14px;
        }
        .highlight {
            border: 1px solid red !important;
        }
        .btn{
            font-size: 12px;
        }
        .table{
            font-size: 13px;
        }
        .place_holder_color::-webkit-input-placeholder {
            color: #0d0f12;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered{
            line-height:38px !important;
        }
        td{
            vertical-align: middle;
        }
        .dt-buttons{
            padding-bottom: 10px;
        }
    </style>

</head>
<body>

<!--wrapper-->
<div class="wrapper">
    <!--sidebar wrapper -->
    <div class="sidebar-wrapper" data-simplebar="true">
        <div class="sidebar-header">
            <div style="margin-left: 35%;">
                <a href="/">
                    <img src="/assets/images/logo.png" class="logo-icon" alt="logo icon" />
                </a>
            </div>
            <div class="toggle-icon ms-auto text-success">
                <i class='bx bx-arrow-to-left'></i>
            </div>
        </div>
        <!--navigation-->
        <ul class="metismenu" id="menu" >
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
                <?php
                $rolde_id = Auth::user()->role_id;
                $groupModule = DB::table('module_permissions')
                    ->join('modules', 'module_permissions.module_id', '=', 'modules.id')
                    ->join('group_modules', 'modules.group_id', '=', 'group_modules.id')
                    ->where('module_permissions.role_id', $rolde_id)->where('module_permissions.a_read', 1)
                    ->select('group_modules.*')->distinct('group_modules.id,group_modules.name,group_modules.icon')
                    ->get();

                ?>
                @foreach($groupModule as $item)
                    <li style="border-top: solid 1px #d6d6d6;border-bottom: solid 1px #d6d6d6">
                        <a class="has-arrow" href="javascript:;">
                            <div class="parent-icon"><i class='bx bx-{{$item->icon}}'></i>
                            </div>
                            <div class="menu-title">{{$item->name}}</div>
                        </a>
                        <ul>
                            <?php
                                $group_id = $item->id;
                                $module = DB::table('module_permissions')
                                    ->join('modules', 'module_permissions.module_id', '=', 'modules.id')
                                    ->join('group_modules', 'modules.group_id', '=', 'group_modules.id')
                                    ->where('module_permissions.role_id', $rolde_id)
                                    ->where('module_permissions.a_read', 1)
                                    ->where('modules.group_id', $group_id)
                                    ->select('modules.*')->distinct('modules.id,modules.name,modules.route_name ')
                                    ->orderBy('modules.order_num','asc')
                                    ->get();

                            ?>
                            @foreach($module as $item1)
                            <li>
                                <a href="{{$item1->route_name}}"><i class="bx bx-right-arrow-alt"></i>{{$item1->name}}</a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            @endguest
        </ul>
        <!--end navigation-->          
    </div>
    <!--end sidebar wrapper -->
    <!--start header -->
    <header>
        <div class="topbar d-flex align-items-center">
            <nav class="navbar navbar-expand">
                <div class="mobile-toggle-menu">
                    <i class='bx bx-menu'></i>
                </div>
                <div class="search-bar flex-grow-1">
                    <h5 class="text-success"><i class="bx bx-coin-stack"></i><u>Ticket System</u></h5>

                </div>
                <div class="top-menu ms-auto">
                    <ul class="navbar-nav align-items-center">                       
                        <li class="nav-item dropdown dropdown-large">
                            <a class="nav-link arrow-none waves-effect nav-user" href="javascript:action_change_language();" role="button" aria-haspopup="false" aria-expanded="false">
                               
                                @guest
                                @else
                                @if(app()->getLocale() == 'kh')
                                    <img style="width:28px;height:28px;" title="Change language" src="/assets/images/flag-en-round-icon-64.png" alt="Language">
                                @else
                                    <img style="width:28px;height:28px;" title="Change language" src="/assets/images/flag-kh-round-icon-64.png" alt="Language">
                                @endif
                                @endguest
                            </a>
                        </li>
                    </ul>
                </div>
                <?php 
                   $profile_image = auth()->user()->image;
                ?>
                <div class="user-box dropdown">
                    <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{$profile_image != null ? $profile_image : '/assets/images/user.png'}}" class="user-img" alt="user avatar"/>
                        <div class="user-info ps-3">
                            @guest
                                <p class="user-name mb-0">Ticket System</p>
                            @else
                                <p class="user-name mb-0"> {{ Auth::user()->name }}</p>
                            @endguest
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">                       
                        <li>
                            <a class="dropdown-item" href="{{ route('users.change_password') }}"><i class="bx bx-lock-alt"></i><span>{{ __('label.changePassword') }}</span></a>
                        </li>                      
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            <i class='bx bx-log-out'></i><span> {{ __('label.logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </ul>
                </div>
            </nav>
        </div>
    </header>
    <!--end header -->
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            @yield('content')
        </div>
    </div>
    <!--end page wrapper -->
    <!--start overlay-->
    <div class="overlay toggle-icon"></div>
    <!--end overlay-->
    <!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
    <!--End Back To Top Button-->
    <footer class="page-footer">
        <p class="mb-0">Copyright ©2023 KSL Ticket. All rights reserved.</p>
    </footer>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    </script>
    <script src="/assets/plugins/metismenu/js/metisMenu.min.js"></script>   
    @guest
    @else
        <script>
            function action_change_language(){
                var lan = "{{ app()->getLocale() }}";
                $.ajax({
                    type:'POST',
                    url:"{{ route('user.change_lan') }}",
                    data:{
                        lang: lan
                    },
                    success:function(result){
                        // console.log(result);
                        if(result.code == 0){
                            location.reload();
                        }
                    }
                });
                
               
            }
        </script>
    @endguest
    
</div>
<!--end wrapper-->

</body>
</html>
