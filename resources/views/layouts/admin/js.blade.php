<script src="{{ asset('assets-admin') }}/vendor/libs/jquery/jquery.js"></script>
<script src="{{ asset('assets-admin') }}/vendor/libs/popper/popper.js"></script>
<script src="{{ asset('assets-admin') }}/vendor/js/bootstrap.js"></script>
<script src="{{ asset('assets-admin') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

<script src="{{ asset('assets-admin') }}/vendor/js/menu.js"></script>

<script src="{{ asset('assets-admin') }}/vendor/libs/apex-charts/apexcharts.js"></script>

<script src="{{ asset('assets-admin') }}/js/main.js"></script>

<script src="{{ asset('assets-admin') }}/js/dashboards-analytics.js"></script>

<script async defer src="{{ asset('assets-admin/libs/github-buttons/buttons.js') }}"></script>
<script src="{{ asset('assets-admin/libs/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets-admin/libs/datatables/dataTables.bootstrap5.min.js') }}"></script>

<!-- Choice.js -->
<script src="{{ asset('assets-admin/libs/choicesjs/choices.min.js') }}"></script>

<!-- TinyMCE -->
<script src="{{ asset('assets-admin/js/tinymce/tinymce.min.js') }}"></script>

<!-- SweetAlert2 JS -->
<script src="{{ asset('assets-admin/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>

<!-- SweetAlert Component -->
@include('components.sweetalert')

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
