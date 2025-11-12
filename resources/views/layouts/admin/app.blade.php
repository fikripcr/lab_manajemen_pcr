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
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('assets-admin') }}/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard - Analytics | Sneat - Bootstrap 5 HTML Admin Template - Pro</title>

    <meta name="description" content="" />

    <link rel="icon" type="image/x-icon" href="{{ asset('assets-admin') }}/img/favicon/favicon.ico" />

    @include('layouts.admin.css')

    <script src="{{ asset('assets-admin') }}/vendor/js/helpers.js"></script>

    {{-- Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the --}}
    {{-- Config: Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file. --> --}}

    <head>
        <script src="{{ asset('assets-admin') }}/js/config.js"></script>
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

                        @include('layouts.admin.footer')

                        <div class="content-backdrop fade"></div>
                    </div>
                </div>
            </div>

            <div class="layout-overlay layout-menu-toggle"></div>
        </div>

        @include('layouts.admin.js')
</body>

</html>
