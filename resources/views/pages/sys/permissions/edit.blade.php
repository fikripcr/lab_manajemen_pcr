<div class="modal-header">
    <h4 class="modal-title">Edit Permission</h4>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="editPermissionForm" action="{{ route('sys.permissions.update', $permission->encrypted_id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="mb-3">
            <label for="editName" class="form-label">Permission Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="editName" name="name" value="{{ old('name', $permission->name) }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text">Use lowercase letters and underscores only (e.g., manage users, view dashboard)</div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update Permission</button>
    </div>
</form>

<script>
    // Check if form is in a modal context or if it's a page load
    if ($('#modalAction').length) {
        // Inside modal context
        $('#editPermissionForm').on('submit', function(e) {
            e.preventDefault();

            // Disable submit button to prevent multiple submissions
            const submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true).text('Updating...');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',  // Using POST method with _method put in the form
                data: $(this).serialize(),
                success: function(response) {
                    // Show success message with higher z-index to appear above modal
                    showSuccessMessage('Success!', 'Permission updated successfully.').then((result) => {
                        // Close the modal
                        $('#modalAction').modal('hide');
                        // Reload the DataTable
                        if (typeof table !== 'undefined') {
                            table.ajax.reload();
                        }
                    });
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    if(errors) {
                        if(errors.name) {
                            $('#editName').addClass('is-invalid');
                            $('#editName').siblings('.invalid-feedback').text(errors.name[0]);
                        }
                    } else {
                        showErrorMessage('Error!', xhr.responseJSON.message || 'An error occurred');
                    }
                },
                complete: function() {
                    // Re-enable submit button
                    submitBtn.prop('disabled', false).text('Update Permission');
                }
            });
        });
    }
</script>
