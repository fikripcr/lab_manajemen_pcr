<!DOCTYPE html>

<!--
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
-->
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="{{ url('build/assets/') }}/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{config('app.name')}}</title>

    <meta name="description" content="" />

    <link rel="icon" type="image/x-xicon" href="{{ Vite::asset('resources/assets/admin/img/favicon/favicon.ico') }}" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('layouts.admin.css')

    <!-- Vite Entry Points -->
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])

    @stack('css')
</head>
<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            @include('layouts.admin.sidebar')

            <div class="layout-page">

                @include('layouts.admin.header')

                <div class="content-wrapper">

                    <div class="container-xxl flex-grow-1 container-p-y">

                        @yield('content')

                    </div>
                    @include('layouts.admin.footer')
                </div>
            </div>

            <div class="layout-overlay layout-menu-toggle"></div>
        </div>

        <!-- Global Search Modal Component -->
        <x-admin.global-search-modal />

        @include('layouts.admin.js')

        @stack('scripts')


    </body>
</html>
