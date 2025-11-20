<script src="{{ asset('assets-admin') }}/vendor/libs/jquery/jquery.min.js"></script>
<script src="{{ asset('assets-admin') }}/vendor/libs/popper/popper.min.js"></script>
<script src="{{ asset('assets-admin') }}/vendor/js/bootstrap.min.js"></script>
<script src="{{ asset('assets-admin') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="{{ asset('assets-admin') }}/vendor/js/menu.min.js"></script>

<script src="{{ asset('assets-admin') }}/js/main.js"></script>


<script src="{{ asset('assets-admin/libs/github-buttons/buttons.min.js') }}"></script>

<!-- Choice.js -->
<script src="{{ asset('assets-admin/libs/choicesjs/choices.min.js') }}"></script>


<!-- SweetAlert2 JS -->
<script src="{{ asset('assets-admin/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets-admin/js/custom/sweetalert-utils.js') }}"></script>


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
<script src="{{ asset('assets-admin/js/custom/notifications.js') }}"></script>
<script src="{{ asset('assets-admin/js/custom/global-search.js') }}"></script>
