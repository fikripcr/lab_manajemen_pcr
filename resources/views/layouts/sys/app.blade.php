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
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{ config('app.name') }}</title>

    <meta name="description" content="" />

    {{-- TODO: Move favicon to a location that is not dependent on the old asset structure. --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('assets-sys') }}/img/favicon/favicon.ico" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite([
        'resources/css/sys.css',
        'resources/js/sys.js'
    ])

    <!-- Define application routes for JavaScript -->
    <script>
        window.appRoutes = {
            notificationsUnreadCount: '{{ route('notifications.unread-count') }}',
            notificationsIndex: '{{ route('notifications.index') }}',
            notificationsDropdownData: '{{ route('notifications.dropdown-data') }}',
            notificationsMarkAllAsRead: '{{ route('notifications.mark-all-as-read') }}',
            globalSearch: '{{ route('global-search') }}',
        };
    </script>

    @stack('css')
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            @include('layouts.sys.sidebar')

            <div class="layout-page">

                @include('layouts.sys.header')

                <div class="content-wrapper">

                    <div class="container-xxl flex-grow-1 container-p-y">

                        @yield('content')

                    </div>
                    @include('layouts.sys.footer')
                </div>
            </div>

            <div class="layout-overlay layout-menu-toggle"></div>
        </div>

        <!-- Global Search Modal Component -->
        <x-sys.modal-global-search />

        <!-- Global Generic Modal -->
        <div class="modal fade" id="modalAction" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" id="modalContent">
                    <div class="modal-header">
                        <h5 class="modal-title">Loading...</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center py-5">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @stack('scripts')


</body>

</html>
