
<form id="createPermissionForm" action="{{ route('sys.permissions.store',$permission->name) }}" method="POST">
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
@push('scripts')
    <script>
        document.getElementById('createPermissionForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Creating...';

            const formData = new FormData(this);

            axios.post(this.getAttribute('action'), formData)
            .then(function(response) {
                // Show success message
                showSuccessMessage('Success!', 'Permission created successfully.').then((result) => {
                    // Close the modal
                    const modalElement = document.getElementById('modalAction');
                    const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                    modal.hide();

                    // Reload the DataTable
                    if (typeof table !== 'undefined') {
                        table.ajax.reload();
                    }
                });
            })
            .catch(function(error) {
                if (error.response && error.response.data && error.response.data.errors) {
                    const errors = error.response.data.errors;
                    if(errors.name) {
                        const nameField = document.getElementById('name');
                        nameField.classList.add('is-invalid');
                        const feedbackElement = nameField.parentNode.querySelector('.invalid-feedback');
                        if (feedbackElement) {
                            feedbackElement.textContent = errors.name[0];
                        }
                    }
                } else {
                    showErrorMessage('Error!', error.response?.data?.message || 'An error occurred');
                }
            })
            .finally(function() {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });
    </script>
@endpush
