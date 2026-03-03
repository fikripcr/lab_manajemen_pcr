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

// Note: Bootstrap is already loaded globally via sys.js/admin.js
// Use window.bootstrap instead of importing again to prevent duplicate instance conflicts

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

    // Fix Bootstrap 5 Modal Focus Trap issue with Third-Party Plugins (Select2 & TinyMCE/HugeRTE)
    // This allows spacebar and clicking inside the editor/dropdown to work
    document.addEventListener('focusin', function (e) {
        if (e.target.closest('.tox-tinymce, .tox-tinymce-aux, .moxman-window, .tam-assetmanager-root') !== null ||
            e.target.closest('.select2-search__field') !== null ||
            e.target.closest('.select2-container') !== null) {
            e.stopImmediatePropagation();
        }
    });

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

        // Capture clicked button name/value (since FormData constructor won't catch it)
        const $clickedBtn = $(document.activeElement);
        if ($clickedBtn.is('button[type="submit"]') && $clickedBtn.attr('name')) {
            formData.append($clickedBtn.attr('name'), $clickedBtn.val());
        }

        // Disable submit button
        $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Processing...');

        // Show loading
        showLoadingMessage('Processing...', 'Please wait');

        // Send request
        axios({
            method: method,
            url: url,
            data: formData,
            headers: {
                'Content-Type': 'multipart/form-data',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(function (response) {
                // Close modal if exists
                const $modal = $form.closest('.modal');
                if ($modal.length && typeof window.bootstrap !== 'undefined') {
                    const modalInstance = window.bootstrap.Modal.getInstance($modal[0]);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }

                // Success
                // Reload DataTable immediately
                if ($.fn.DataTable && $('.dataTable').length) {
                    $('.dataTable').DataTable().ajax.reload(null, false);
                }

                // Reset form immediately
                $form[0].reset();
                $form.find('.is-invalid').removeClass('is-invalid'); // Clean validation states
                $form.find('.invalid-feedback').remove();

                // Success (Toast)
                showSuccessMessage(response.data.message || 'Success');

                // Fire custom event
                // Fire custom event
                const successEvent = new CustomEvent('ajax-form:success', {
                    detail: { response: response.data, form: $form[0] },
                    bubbles: true,
                    cancelable: true
                });
                $form[0].dispatchEvent(successEvent);

                // Fire jQuery event (for delegated listeners)
                $form.trigger('ajax-form:success', [response.data, $form[0]]);

                // Redirect immediately if specified
                if (response.data.redirect) {
                    window.location.href = response.data.redirect;
                }
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

                showErrorMessage('Error!', errorMessage);
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

        showDeleteConfirmation(title, text)
            .then((result) => {
                if (result.isConfirmed) {
                    // Show loading while deleting
                    showLoadingMessage('Deleting...', 'Please wait');

                    axios.delete(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                        .then(response => {
                            return { value: response };
                        })
                        .catch(error => {
                            return { value: { error: error } };
                        })
                        .then(result => {
                            if (result.value && result.value.error) {
                                // Error handling
                                const error = result.value.error;
                                console.error(error);
                                let errorMessage = 'Failed to delete item';
                                if (error.response && error.response.data && error.response.data.message) {
                                    errorMessage = error.response.data.message;
                                }
                                showErrorMessage('Error!', errorMessage);
                                return;
                            }

                            // Success handling
                            const response = result.value;

                            // Reload DataTable immediately
                            if ($.fn.DataTable && $('.dataTable').length) {
                                $('.dataTable').DataTable().ajax.reload(null, false);
                            }

                            // Show Success Toast
                            showSuccessMessage(response.data.message || 'Deleted!');

                            // Redirect if specified (Immediate)
                            if (response.data.redirect) {
                                window.location.href = response.data.redirect;
                            }
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
        const url = $btn.data('url') || $btn.attr('href');
        const target = $btn.data('modal-target') || '#modalAction';
        const title = $btn.data('modal-title');
        const size = $btn.data('modal-size'); // modal-sm, modal-lg, modal-xl

        const $modal = $(target);
        const $modalContent = $modal.find('#modalContent');

        // Initialize modal if not already initialized
        let bootstrapModal = window.bootstrap.Modal.getInstance($modal[0]);
        if (!bootstrapModal) {
            // Disable native focus trap so it doesn't break HugeRTE / Select2 search spaces
            bootstrapModal = new window.bootstrap.Modal($modal[0], {
                focus: false
            });
        }
        
        // Remove tabindex to prevent focus stealing by the modal wrapper
        $modal.removeAttr('tabindex');

        // Handle size
        const $modalDialog = $modal.find('.modal-dialog');
        $modalDialog.removeClass('modal-sm modal-lg modal-xl').addClass(size || '');

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
        axios.get(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(function (response) {
                $modalContent.html(response.data);
                // Re-initialize components
                if (typeof window.initOfflineSelect2 === 'function') {
                    window.initOfflineSelect2();
                }
                if (typeof window.initFlatpickr === 'function') {
                    window.initFlatpickr();
                }
                if (typeof window.initFilePond === 'function') {
                    window.initFilePond();
                }

                // Re-init HugeRTE if present
                if (typeof window.loadHugeRTE === 'function') {
                    $modalContent.find('textarea.form-control').each(function () {
                        const id = $(this).attr('id');
                        if (id && $(this).closest('.tinymce-container').length > 0) {
                            window.loadHugeRTE('#' + id, {
                                height: 200,
                                menubar: false,
                                statusbar: false,
                                setup: function (editor) {
                                    editor.on('change', function () {
                                        editor.save();
                                    });
                                }
                            });
                        }
                    });
                }
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
        const $modal = $(this);
        const $form = $modal.find('.ajax-form');
        
        // Clean up HugeRTE/TinyMCE instances to prevent initialization issues on reopen
        if (typeof window.hugerte !== 'undefined') {
            $modal.find('textarea.form-control').each(function () {
                const id = $(this).attr('id');
                if (id && window.hugerte.get(id)) {
                    window.hugerte.remove('#' + id);
                }
            });
        }
        
        if ($form.length) {
            $form[0].reset();
            $form.find('.is-invalid').removeClass('is-invalid');
            $form.find('.invalid-feedback').remove();
        }
    });
}
