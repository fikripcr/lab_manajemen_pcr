<script src="{{ asset('assets-sys') }}/vendor/libs/jquery/jquery.min.js"></script>
<script src="{{ asset('assets-sys') }}/vendor/libs/popper/popper.min.js"></script>
<script src="{{ asset('assets-sys') }}/vendor/js/bootstrap.min.js"></script>
<script src="{{ asset('assets-sys') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="{{ asset('assets-sys') }}/vendor/js/menu.min.js"></script>

<script src="{{ asset('assets-sys') }}/js/main.js"></script>


<script src="{{ asset('assets-sys/libs/github-buttons/buttons.min.js') }}"></script>

<!-- Choice.js -->
<script src="{{ asset('assets-sys/libs/choicesjs/choices.min.js') }}"></script>


<!-- SweetAlert2 JS -->
<script src="{{ asset('assets-global/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets-global/js/custom/sweetalert-utils.js') }}"></script>


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

<!-- Custom JavaScript files -->
<script src="{{ asset('assets-sys/js/custom/notifications.js') }}"></script>
<script src="{{ asset('assets-sys/js/custom/global-search.js') }}"></script>