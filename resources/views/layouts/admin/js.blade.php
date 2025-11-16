<script src="{{ asset('assets-admin') }}/vendor/libs/jquery/jquery.js"></script>
<script src="{{ asset('assets-admin') }}/vendor/libs/popper/popper.js"></script>
<script src="{{ asset('assets-admin') }}/vendor/js/bootstrap.js"></script>
<script src="{{ asset('assets-admin') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

<script src="{{ asset('assets-admin') }}/vendor/js/menu.js"></script>

<script src="{{ asset('assets-admin') }}/vendor/libs/apex-charts/apexcharts.js"></script>

<script src="{{ asset('assets-admin') }}/js/main.js"></script>

<script src="{{ asset('assets-admin') }}/js/dashboards-analytics.js"></script>

<script async defer src="https://buttons.github.io/buttons.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>

<!-- Choice.js -->
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<!-- TinyMCE -->
<script src="{{ asset('assets-admin/js/tinymce/tinymce.min.js') }}"></script>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle "Mark all as read" button
    const markAllAsReadBtn = document.getElementById('markAllAsReadBtn');
    if (markAllAsReadBtn) {
        markAllAsReadBtn.addEventListener('click', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menandai semua notifikasi sebagai telah dibaca?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, tandai semua',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route('notifications.mark-all-as-read') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Sukses!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Refresh the page to update notification counts
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Gagal menandai notifikasi sebagai telah dibaca',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menandai notifikasi sebagai telah dibaca',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
                }
            });
        });
    }
});
</script>
