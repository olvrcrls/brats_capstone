<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="author" content="Abilar, Arconada, Cabagua, Carlos, Escala, Flores">
    <meta name="description" content="Capstone project for bus reservation and ticketing system by Abilar, Arconada, Cabagua, Carlos, Escala, and Flores">
    <meta name="title" content="Bus Reservation And Ticketing System - A capstone project. CY: 2016-2017">
    <meta name="url" content="https://websitename.com">
    <meta name="site_name" content="Bus Reservation And Ticketing System">
    <meta name="locale" content="en_US">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if ( isset($title) )
    <title> {{ $title }} </title>
    @else
    <title>Bus Reservation And Ticketing System</title>
    @endif
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="/materialize/css/materialize.css">
        <link rel="stylesheet" href="/css/font-awesome.min.css">
        <link rel="stylesheet" href="/css/layouts/app.css">
        <script type="text/javascript" src="/js/jquery.js"></script>
        <script type="text/javascript" src="/materialize/js/materialize.js"></script>
        <script type="text/javascript">
            $( document ).ready(function(){
                $(".button-collapse").sideNav();
            });
        </script>
        @yield('head')
</head>
<body class="blue-grey lighten-5"> 
    <nav class="yellow accent-3 z-depth-1">
            <div class="brats-border nav-wrapper">
                <a href="/" class="brand-logo black-text"><b>Bus Reservation And Ticketing System </b><i class="fa fa-bus"></i></a>
                <a href="#!" data-activates="mobile-demo" class="button-collapse black-text"><i class="fa fa-bars"></i></a>
                <ul class="right hide-on-med-and-down">
                    <li><a class="black-text" href="{{ url('/routes') }}"><i class="fa fa-btn fa-road"></i> Route & Schedule Details</a></li>
                    <li><a class="black-text" href="{{ url('/') }}"><i class="fa fa-btn fa-calendar"></i> Schedule A Trip</a></li>                    
                    <li><a class="black-text" href="{{ url('/manage/trip') }}"><i class="fa fa-btn fa-cogs"></i> Manage Booked Trip</a></li>
                    <!-- <li><a class="black-text" href="{{ url('/contact') }}"><i class="fa fa-phone"></i> Contact Us</a></li> -->
                    <li><a class="black-text" href="{{ url('/about') }}"><i class="fa fa-users"></i> About Us</a></li>
                </ul>
                <ul class="side-nav" id="mobile-demo">
                    <li><a class="black-text" href="{{ url('/routes') }}"><i class="fa fa-btn fa-road"></i> Route & Schedule Details</a></li>
                    <li><a class="black-text" href="{{ url('/') }}"><i class="fa fa-btn fa-calendar"></i> Schedule A Trip</a></li>
                    <li><a class="black-text" href="{{ url('/manage/trip') }}"><i class="fa fa-btn fa-cogs"></i> Manage Booked Trip</a></li>
                    <!-- <li><a class="black-text" href="{{ url('/contact') }}"><i class="fa fa-phone"></i> Contact Us</a></li> -->
                    <li><a class="black-text" href="{{ url('/about') }}"><i class="fa fa-users"></i> About Us</a></li>
                </ul>
            </div>
        </nav>

    @yield('content')

        <footer class="page-footer yellow darken-1">
            <div class="container">
                <div class="row">
                    <div class="col l6 s12">
                        <h5 class="black-text text-darken-3">Bus Reservation And Ticketing System</h5>
                        <p class="white-text text-accent-3">Online Reservation of your desire Trips with your preferred Seats</p>
                    </div>
                    <div class="col l4 offset-l2 s12">
                        <h5 class="black-text text-darken-1"><i class="fa fa-tag"></i> Links</h5>
                        <ul class="white-text">
                            <li><a class="white-text" href="#!">Route & Schedule Details</a></li>
                            <li><a class="white-text" href="{{ url('/') }}">Schedule A Trip</a></li>
                            <li><a class="white-text" href="{{ url('/manage/trip') }}">Manage Booked Trip</a></li>
                            <!-- <li><a class="white-text" href="{{ url('/contact') }}">Contact Us</a></li> -->
                            <li><a class="white-text" href="{{ url('/about') }}">About Us</a></li>
                        </ul>
                    </div>
                </div>
            </div>
          
            <div class="footer-copyright">
                <div class="container brown-text text-accent-3">
                    BSIT 4-1D JJJLOR &copy; {{ date('Y') }} Copyright
                   <!--  <a class="blue-text right" href="#!">More Links</a> -->
                </div>
            </div>
        </footer>
        @yield('footer')
</body>
</html>