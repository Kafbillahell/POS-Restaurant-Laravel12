<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
     <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Pos Cafe Laravel</title>
    <link href="{{asset('assets/')}}/extra-libs/c3/c3.min.css" rel="stylesheet">
    <link href="{{asset('assets/')}}/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="{{asset('assets/')}}/extra-libs/jvector/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <link href="{{asset('dist/')}}/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    @yield('styles')
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<style>
/* **CSS Tambahan untuk Menghilangkan Margin Body Bawaan** */
body {
    margin: 0; 
    padding: 0;
}
/* **Asumsi Tinggi Header** */
/* Tambahkan ini ke custom.css atau di bawah body {} jika allbar adalah header fixed */
/* .page-wrapper {
    padding-top: 100px; 
} */
</style>

<body>

<body>
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    @include('layouts.allbar')

        

    <div class="page-wrapper" style="padding-top: 100px;"> 
        <div class="content-wrapper" style="margin-left: 250px; padding: 20px; /* HILANGKAN margin-top: 100px; */ padding-left: 20px; padding-right: 20px; min-height: 100vh;">
            @yield('content')
        </div>
    </div>


    <script src="{{asset('assets/')}}/libs/jquery/dist/jquery.min.js"></script>
    <script src="{{asset('assets/')}}/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="{{asset('assets/')}}/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="{{asset('dist/')}}/js/app-style-switcher.js"></script>
    <script src="{{asset('dist/')}}/js/feather.min.js"></script>
    <script src="{{asset('assets/')}}/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="{{asset('dist/')}}/js/sidebarmenu.js"></script>
    <script src="{{asset('dist/')}}/js/custom.min.js"></script>
    <script src="{{asset('assets/')}}/extra-libs/c3/d3.min.js"></script>
    <script src="{{asset('assets/')}}/extra-libs/c3/c3.min.js"></script>
    <script src="{{asset('assets/')}}/libs/chartist/dist/chartist.min.js"></script>
    <script src="{{asset('assets/')}}/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
    <script src="{{asset('assets/')}}/extra-libs/jvector/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="{{asset('assets/')}}/extra-libs/jvector/jquery-jvectormap-world-mill-en.js"></script>
    <script src="{{asset('dist/')}}/js/pages/dashboards/dashboard1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/feather-icons"></script>
<script>feather.replace()</script>


@stack('scripts')
  @yield('scripts') 
</body>
        
</html>