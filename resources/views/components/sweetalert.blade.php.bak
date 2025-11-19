<script>
    // SweetAlert function for delete confirmation
    function confirmDelete(url, title = 'Delete this item?', text = 'Are you sure you want to delete this item? This action cannot be undone.') {
        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        '_method': 'DELETE',
                        '_token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Deleted!',
                                response.message,
                                'success'
                            );
                            // Reload the DataTable
                            if (typeof table !== 'undefined') {
                                table.ajax.reload();
                            } else {
                                location.reload();
                            }
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred while deleting the item.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire(
                            'Error!',
                            errorMessage,
                            'error'
                        );
                    }
                });
            }
        });
    }

    // SweetAlert function for general confirmation
    function confirmAction(title, text, confirmText = 'Yes', callback) {
        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: confirmText,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                callback();
            }
        });
    }

    // SweetAlert function for success messages
    function showSuccessMessage(title, text = '') {
        Swal.fire({
            icon: 'success',
            title: title,
            text: text,
            showConfirmButton: false,
            timer: 1500
        });
    }

    // SweetAlert function for error messages
    function showErrorMessage(title, text = '') {
        Swal.fire({
            icon: 'error',
            title: title,
            text: text
        });
    }

    // SweetAlert function for info messages
    function showInfoMessage(title, text = '') {
        Swal.fire({
            icon: 'info',
            title: title,
            text: text
        });
    }

    // SweetAlert function for warning messages
    function showWarningMessage(title, text = '') {
        Swal.fire({
            icon: 'warning',
            title: title,
            text: text
        });
    }
</script>