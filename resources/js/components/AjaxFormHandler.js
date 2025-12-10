/**
 * Global AJAX Form Handler
 * 
 * Usage:
 * 1. Add class 'ajax-form' to your form
 * 2. Form will auto-submit via Axios
 * 3. Success/error handled with SweetAlert2
 * 4. DataTable auto-reloads if present
 * 
 * Example:
 * <form class="ajax-form" action="/api/endpoint" method="POST">
 *     <input name="name" required>
 *     <button type="submit">Save</button>
 * </form>
 */

// Wait for both DOM and jQuery to be ready
if (typeof window.jQuery !== 'undefined') {
    initAjaxFormHandler();
} else {
    document.addEventListener('DOMContentLoaded', function () {
        // Wait a bit for jQuery to load
        setTimeout(initAjaxFormHandler, 100);
    });
}

function initAjaxFormHandler() {
    if (typeof $ === 'undefined') {
        console.error('AjaxFormHandler: jQuery not loaded');
        return;
    }

    /**
     * Handle AJAX form submission
     */
    $(document).on('submit', '.ajax-form', function (e) {
        e.preventDefault();

        const $form = $(this);
        const $submitBtn = $form.find('button[type="submit"]');
        const originalBtnText = $submitBtn.html();
        const formData = new FormData(this);
        const method = $form.attr('method') || 'POST';
        const url = $form.attr('action');

        // Disable submit button
        $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Processing...');

        // Show loading
        Swal.fire({
            title: 'Processing...',
            text: 'Please wait',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => Swal.showLoading()
        });

        // Send request
        axios({
            method: method,
            url: url,
            data: formData,
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
            .then(function (response) {
                // Close modal if exists
                const $modal = $form.closest('.modal');
                if ($modal.length && typeof bootstrap !== 'undefined') {
                    const modalInstance = bootstrap.Modal.getInstance($modal[0]);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }

                // Success
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.data.message || 'Operation completed successfully',
                    timer: 2000,
                    timerProgressBar: true
                }).then(() => {
                    // Reload DataTable if exists
                    if ($.fn.DataTable && $('.dataTable').length) {
                        $('.dataTable').DataTable().ajax.reload(null, false);
                    }

                    // Reset form
                    $form[0].reset();

                    // Redirect if specified in response
                    if (response.data.redirect) {
                        window.location.href = response.data.redirect;
                    }
                });
            })
            .catch(function (error) {
                // Error handling
                let errorMessage = 'An error occurred';

                if (error.response) {
                    // Server responded with error
                    if (error.response.data.message) {
                        errorMessage = error.response.data.message;
                    } else if (error.response.data.errors) {
                        // Validation errors
                        const errors = error.response.data.errors;
                        errorMessage = Object.values(errors).flat().join('<br>');
                    }
                } else if (error.request) {
                    // Request made but no response
                    errorMessage = 'No response from server';
                } else {
                    // Error in request setup
                    errorMessage = error.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: errorMessage,
                    confirmButtonText: 'OK'
                });
            })
            .finally(function () {
                // Re-enable submit button
                $submitBtn.prop('disabled', false).html(originalBtnText);
            });
    });

    /**
     * Handle AJAX delete with confirmation
     * 
     * Usage:
     * <button class="ajax-delete" data-url="/api/endpoint/1" data-title="Delete Item?">Delete</button>
     */
    $(document).on('click', '.ajax-delete', function (e) {
        e.preventDefault();

        const $btn = $(this);
        const url = $btn.data('url');
        const title = $btn.data('title') || 'Are you sure?';
        const text = $btn.data('text') || 'This action cannot be undone!';

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
                // Show loading
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });

                // Send delete request
                axios.delete(url)
                    .then(function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.data.message || 'Item has been deleted',
                            timer: 2000,
                            timerProgressBar: true
                        }).then(() => {
                            // Reload DataTable if exists
                            if ($.fn.DataTable && $('.dataTable').length) {
                                $('.dataTable').DataTable().ajax.reload(null, false);
                            }

                            // Redirect if specified
                            if (response.data.redirect) {
                                window.location.href = response.data.redirect;
                            }
                        });
                    })
                    .catch(function (error) {
                        let errorMessage = 'Failed to delete item';

                        if (error.response && error.response.data.message) {
                            errorMessage = error.response.data.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });
                    });
            }
        });
    });

    /**
     * Handle Generic AJAX Modal
     * Usage: <a href="#" class="ajax-modal-btn" data-url="/path/to/content">Open Modal</a>
     */
    $(document).on('click', '.ajax-modal-btn', function (e) {
        e.preventDefault();

        const $btn = $(this);
        const url = $btn.data('url');
        const target = $btn.data('modal-target') || '#modalAction';
        const title = $btn.data('modal-title');

        const $modal = $(target);
        const $modalContent = $modal.find('#modalContent');

        // Initialize modal if not already initialized
        let bootstrapModal = bootstrap.Modal.getInstance($modal[0]);
        if (!bootstrapModal) {
            bootstrapModal = new bootstrap.Modal($modal[0]);
        }

        // Show modal with loading state
        bootstrapModal.show();
        $modalContent.html(`
            <div class="modal-header">
                <h5 class="modal-title">${title || 'Loading...'}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `);

        // Fetch content
        axios.get(url)
            .then(function (response) {
                $modalContent.html(response.data);
            })
            .catch(function (error) {
                console.error(error);
                let errorMessage = 'Failed to load content';
                if (error.response && error.response.data && error.response.data.message) {
                    errorMessage = error.response.data.message;
                }

                $modalContent.html(`
                    <div class="modal-header">
                        <h5 class="modal-title">Error</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center py-5">
                        <div class="text-danger mb-3">
                            <i class="bx bx-error-circle bx-lg"></i>
                        </div>
                        <p class="text-danger mb-0">${errorMessage}</p>
                    </div>
                `);
            });
    });

    /**
     * Handle modal form reset when modal is hidden
     */
    $(document).on('hidden.bs.modal', '.modal', function () {
        const $form = $(this).find('.ajax-form');
        if ($form.length) {
            $form[0].reset();
            $form.find('.is-invalid').removeClass('is-invalid');
            $form.find('.invalid-feedback').remove();
        }
    });
}
