<script src="{{ asset('assets-sys') }}/vendor/js/menu.min.js"></script>

<script src="{{ asset('assets-sys') }}/js/main.js"></script>


<script src="{{ asset('assets-sys/libs/github-buttons/buttons.min.js') }}"></script>



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
