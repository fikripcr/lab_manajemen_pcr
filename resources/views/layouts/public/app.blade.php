<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>{{config('app.name')}}</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Favicons -->
  <link href="{{ asset('images/favicon.png') }}" rel="icon">
  <link href="{{ asset('images/apple-touch-icon.png') }}" rel="apple-touch-icon">

  @include('layouts.public.css')
  @vite(['resources/css/public.css', 'resources/js/public.js'])

  <!-- =======================================================
  * Template Name: TheProperty
  * Template URL: https://bootstrapmade.com/theproperty-bootstrap-real-estate-template/
  * Updated: Aug 05 2025 with Bootstrap v5.3.7
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

  @include('layouts.public.header')

  <main class="main">

    @yield('content')

  </main>

  @include('layouts.public.footer')

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  {{-- <div id="preloader"></div> --}}

  @include('layouts.public.js')
  @stack('scripts')

</body>

</html>
