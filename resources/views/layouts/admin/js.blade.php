<!-- Core/Vendor JS are now bundled via Vite in resources/js/admin.js -->

<!-- Define application routes for JavaScript (Required by bundled scripts) -->
<script>
    window.appRoutes = {
        notificationsUnreadCount: '{{ route('notifications.unread-count') }}',
        notificationsIndex: '{{ route('notifications.index') }}',
        notificationsDropdownData: '{{ route('notifications.dropdown-data') }}',
        notificationsMarkAllAsRead: '{{ route('notifications.mark-all-as-read') }}',
        globalSearch: '{{ route('global-search') }}',
    };
</script>
