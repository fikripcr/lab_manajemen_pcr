<div class="modal-header">
    <h4 class="modal-title">Create New Permission</h4>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="createPermissionForm" action="{{ route('permissions.store') }}" method="POST">
    @csrf
    <div class="modal-body">
        <div class="mb-3">
            <label for="name" class="form-label">Permission Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text">Use lowercase letters and underscores only (e.g., manage users, view dashboard)</div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Create Permission</button>
    </div>
</form>

<script>
    // Check if form is in a modal context or if it's a page load
    if ($('#modalAction').length) {
        // Inside modal context
        $('#createPermissionForm').on('submit', function(e) {
            e.preventDefault();

            // Disable submit button to prevent multiple submissions
            const submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true).text('Creating...');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // Show success message
                    showSuccessMessage('Success!', 'Permission created successfully.').then((result) => {
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
                            $('#name').addClass('is-invalid');
                            $('#name').siblings('.invalid-feedback').text(errors.name[0]);
                        }
                    } else {
                        showErrorMessage('Error!', xhr.responseJSON.message || 'An error occurred');
                    }
                },
                complete: function() {
                    // Re-enable submit button
                    submitBtn.prop('disabled', false).text('Create Permission');
                }
            });
        });
    }
</script>