
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>FitLife</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=EB+Garamond:400,400i,500,500i,600,600i,700,700i&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('style/css/open-iconic-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('style/css/animate.css')}}">
    
    <link rel="stylesheet" href="{{asset('style/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('style/css/owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{asset('style/css/magnific-popup.css')}}">

    <link rel="stylesheet" href="{{asset('style/css/aos.css')}}">

    <link rel="stylesheet" href="{{asset('style/css/ionicons.min.css')}}">

    <link rel="stylesheet" href="{{asset('style/css/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{asset('style/css/jquery.timepicker.css')}}">

    
    <link rel="stylesheet" href="{{asset('style/css/flaticon.css')}}">
    <link rel="stylesheet" href="{{asset('style/css/icomoon.css')}}">
    <link rel="stylesheet" href="{{asset('style/css/style.css')}}">
     <!-- Icon Font Stylesheet -->
 <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
 <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
  </head>
  <body>

     {{-- start nav --}}
  @include('Layout.nav')
    {{-- END nav --}}

     {{--start content--}}
     @yield('content')
      {{-- END content --}}


           {{-- start footer --}}
      @include('Layout.footer')
           {{-- END footer --}}
   
      
      
    
  
    <!-- loader -->
    <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>
  
  
    <script src="{{asset('style/js/jquery.min.js')}}"></script>
    <script src="{{asset('style/js/jquery-migrate-3.0.1.min.js')}}"></script>
    <script src="{{asset('style/js/popper.min.js')}}"></script>
    <script src="{{asset('style/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('style/js/jquery.easing.1.3.js')}}"></script>
    <script src="{{asset('style/js/jquery.waypoints.min.js')}}"></script>
    <script src="{{asset('style/js/jquery.stellar.min.js')}}"></script>
    <script src="{{asset('style/js/owl.carousel.min.js')}}"></script>
    <script src="{{asset('style/js/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('style/js/aos.js')}}"></script>
    <script src="{{asset('style/js/jquery.animateNumber.min.js')}}"></script>
    <script src="{{asset('style/js/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('style/js/jquery.timepicker.min.js')}}"></script>
    <script src="{{asset('style/js/scrollax.min.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
    <script src="{{asset('style/js/google-map.js')}}"></script>
    <script src="{{asset('style/js/main.js')}}"></script>
      
    </body>
  </html>


