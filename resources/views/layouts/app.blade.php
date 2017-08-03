<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" id = "csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style-responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('css/tab/tabs.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- jquery ui -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
   <!--  slick -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css"/>
    <link href="{{ asset('css/slick.theme.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/fonts/slick.woff')}}">
    <!-- Add the slick-theme.css if you want default styling -->
    
   <!--  css tu viet -->
   <link href="{{ asset('css/handle.css') }}" rel="stylesheet">
   <link href="{{ asset('css/loadmore.css') }}" rel="stylesheet">

</head>
<body>
    <div id="app" >
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">

                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <strong style="font-size: 40px; color: blue">PhicherPro</strong>
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                      
                    </ul>
                        
                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        <li class="navbar-icon">
                            <a href="#">
                                <span>
                                    <input type="text" placeholder="Search a trip or user"> 
                                </span>
                                <span>
                                    <button>
                                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                    </button>
                                </span>
                            </a>
                        </li>
                        <li class="navbar-icon">
                        <a href="#">
                            <button class="btn btn-primary">
                                <span class="glyphicon glyphicon-plus disabled" aria-hidden="true"></span>
                                <span class="visible-xs">Create a trip</span>
                                Create a trip
                            </button>
                        </a>
                        </li>
                        <li class="navbar-icon">
                        <a href="#">
                            <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
                            <span class="visible-xs">Notification</span>
                            Notification
                        </a>
                        </li>
                       
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <span class="name">{{ Auth::user()->name }} </span> 
                                    <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{route('profile', Auth::user()->id)}}">
                                            My profile
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
            @yield('content')
        <div class="row">
            <div class="footer" style="background: #dddddd">
                <h3> Information of co-founder </h3>
                <div class="row">

                        <ul class="social">
                            <li> <a href="#"> <i class=" fa fa-facebook">   </i> </a> </li>
                            <li> <a href="#"> <i class="fa fa-twitter">   </i> </a> </li>
                            <li> <a href="#"> <i class="fa fa-google-plus">   </i> </a> </li>
                            <li> <a href="#"> <i class="fa fa-pinterest">   </i> </a> </li>
                            <li> <a href="#"> <i class="fa fa-youtube">   </i> </a> </li>
                        </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Scripts -->
<!-- <script src="{{ asset('js/app.js') }}"></script> -->
@yield('jq_lib')
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js"></script>
<script src="{{ asset('js/tabs.min.js') }}"></script>
@yield('sc')

</body>
</html>
