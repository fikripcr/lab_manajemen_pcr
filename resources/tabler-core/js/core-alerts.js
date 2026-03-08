/**
 * SweetAlert Utility Functions
 * Centralized functions to handle all SweetAlert calls in the application
 * Following DRY (Don't Repeat Yourself) principle
 */
// Imports
import Swal from 'sweetalert2';
import axios from 'axios';

// --- Global Assignments for Legacy/External Scripts ---
if (typeof window !== 'undefined') {
    window.Swal = Swal;
    window.axios = axios;
}
// Delete Confirmation Alert
function showDeleteConfirmation(title = 'Apakah Anda yakin?', text = 'Tindakan ini tidak dapat dibatalkan!', confirmButtonText = 'Ya, hapus!') {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: confirmButtonText,
        cancelButtonText: 'Batal'
    });
}

// General Confirmation Alert
function showConfirmation(title = 'Apakah Anda yakin?', text = '', confirmButtonText = 'Ya') {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: confirmButtonText,
        cancelButtonText: 'Batal'
    });
}

// Toast configuration mixin
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

// Success Message (Toast Mode)
function showSuccessMessage(title = 'Berhasil!', text = '') {
    return Toast.fire({
        icon: 'success',
        title: title,
        text: text
    });
}

// Error Message
function showErrorMessage(title = 'Kesalahan!', text = '') {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'error',
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: false
    });
}

// Info Message
function showInfoMessage(title = 'Info', text = '') {
    return Swal.fire({
        title: title,
        html: text,
        icon: 'info',
        timer: 2000,
        timerProgressBar: true,
        showConfirmButton: false
    });
}

// Warning Message
function showWarningMessage(title = 'Peringatan!', text = '') {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        timer: 2500,
        timerProgressBar: true,
        showConfirmButton: false
    });
}

// Loading Message (not auto-closing)
function showLoadingMessage(title = 'Memproses...', text = 'Mohon tunggu') {
    return Swal.fire({
        title: title,
        html: text,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

// Form error display (for validation errors)
function showFormErrors(errors) {
    let errorText = '';

    if (typeof errors === 'string') {
        errorText = errors;
    } else if (Array.isArray(errors)) {
        errorText = errors.join('<br>');
    } else if (typeof errors === 'object') {
        errorText = Object.values(errors).flat().join('<br>');
    } else {
        errorText = 'Terjadi kesalahan saat memproses permintaan Anda.';
    }

    return Swal.fire({
        title: 'Kesalahan Validasi!',
        html: errorText,
        icon: 'error',
        timer: 5000,
        timerProgressBar: true
    });
}

// AJAX response handler
function handleAjaxResponse(response, successCallback = null, errorCallback = null) {
    if (response.success) {
        showSuccessMessage(response.title || 'Berhasil!', response.message || '');
        if (successCallback && typeof successCallback === 'function') {
            successCallback(response);
        }
    } else {
        showErrorMessage(response.title || 'Kesalahan!', response.message || response.error || '');
        if (errorCallback && typeof errorCallback === 'function') {
            errorCallback(response);
        }
    }
}

// Bulk action confirmation
function showBulkActionConfirmation(actionName, count, itemType = 'item') {
    return Swal.fire({
        title: `Konfirmasi ${actionName} Massal`,
        text: `Apakah Anda yakin ingin ${actionName.toLowerCase()} ${count} ${itemType}${count > 1 ? '' : ''}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Ya, ${actionName.toLowerCase()}!`,
        cancelButtonText: 'Batal'
    });
}

// General confirmation function
function confirmAction(title, text, confirmText = 'Yes', callback) {
    showConfirmation(title, text, confirmText).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
}

// Delete confirmation with AJAX (for use with controller-generated links)
function confirmDelete(url, tableId = null, title = 'Hapus item ini?', text = 'Apakah Anda yakin ingin menghapus item ini? Tindakan ini tidak dapat dibatalkan.') {
    showDeleteConfirmation(title, text).then((result) => {
        if (result.isConfirmed) {
            axios({
                method: 'POST',
                url: url,
                data: {
                    '_method': 'DELETE',
                }
            })
                .then(function (response) {
                    showSuccessMessage('Terhapus!', response.data.message || 'Item berhasil dihapus!');

                    if (tableId) {
                        // Coba berbagai format nama tabel untuk kompatibilitas
                        let tableInstance = window['DT_' + tableId];

                        // Check if it's an instance of CustomDataTables
                        if (tableInstance && typeof tableInstance.table !== 'undefined') {
                            // Use the DataTables instance from CustomDataTables
                            tableInstance.table.ajax.reload(null, false);
                        } else if (tableInstance && typeof tableInstance.ajax !== 'undefined') {
                            // If it's a direct DataTables instance
                            tableInstance.ajax.reload(null, false);
                        } else {
                            // Fallback: reload the page if table instance is not found
                            console.warn(`Table instance DT_${tableId} or its variants not found or invalid`);
                            location.reload();
                        }
                    } else {
                        location.reload();
                    }
                })
                .catch(function (error) {
                    let errorMessage = 'Terjadi kesalahan saat menghapus item.';
                    if (error.response && error.response.data && error.response.data.message) {
                        errorMessage = error.response.data.message;
                    }
                    showErrorMessage('Kesalahan!', errorMessage);
                });
        }
    });
}

// Mendaftarkan fungsi-fungsi ke window object agar bisa diakses secara global
window.showDeleteConfirmation = showDeleteConfirmation;
window.showConfirmation = showConfirmation;
window.showSuccessMessage = showSuccessMessage;
window.showErrorMessage = showErrorMessage;
window.showInfoMessage = showInfoMessage;
window.showWarningMessage = showWarningMessage;
window.showLoadingMessage = showLoadingMessage;
window.showFormErrors = showFormErrors;
window.handleAjaxResponse = handleAjaxResponse;
window.showBulkActionConfirmation = showBulkActionConfirmation;
window.confirmAction = confirmAction;
window.confirmDelete = confirmDelete;
