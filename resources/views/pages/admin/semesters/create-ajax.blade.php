<div class="modal-header">
    <h4 class="modal-title">Add New Semester</h4>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="createSemesterForm" action="{{ route('semesters.store') }}" method="POST">
    @csrf
    <div class="modal-body">
        <div class="mb-3">
            <label for="tahun_ajaran" class="form-label fw-bold">Tahun Ajaran <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('tahun_ajaran') is-invalid @enderror"
                   id="tahun_ajaran" name="tahun_ajaran"
                   value="{{ old('tahun_ajaran') }}"
                   placeholder="e.g. 2023/2024" required>
            @error('tahun_ajaran')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="semester" class="form-label fw-bold">Semester <span class="text-danger">*</span></label>
            <select class="form-select @error('semester') is-invalid @enderror"
                    id="semester" name="semester" required>
                <option value="">Pilih Semester</option>
                <option value="1" {{ old('semester') == 1 ? 'selected' : '' }}>Ganjil</option>
                <option value="2" {{ old('semester') == 2 ? 'selected' : '' }}>Genap</option>
            </select>
            @error('semester')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="start_date" class="form-label fw-bold">Start Date <span class="text-danger">*</span></label>
            <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                   id="start_date" name="start_date"
                   value="{{ old('start_date') }}" required>
            @error('start_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label fw-bold">End Date <span class="text-danger">*</span></label>
            <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                   id="end_date" name="end_date"
                   value="{{ old('end_date') }}" required>
            @error('end_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">
                    Set as Active Semester
                </label>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Create Semester</button>
    </div>
</form>

<script>
    // Check if form is in a modal context or if it's a page load
    if ($('#modalAction').length) {
        // Inside modal context
        $('#createSemesterForm').on('submit', function(e) {
            e.preventDefault();

            // Disable submit button to prevent multiple submissions
            const submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true).text('Creating...');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // Show success message with higher z-index to appear above modal
                    Swal.fire({
                        title: 'Success!',
                        text: 'Semester created successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        zIndex: 9999
                    }).then((result) => {
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
                        if(errors.tahun_ajaran) {
                            $('#tahun_ajaran').addClass('is-invalid');
                            $('#tahun_ajaran').siblings('.invalid-feedback').text(errors.tahun_ajaran[0]);
                        }
                        if(errors.semester) {
                            $('#semester').addClass('is-invalid');
                            $('#semester').siblings('.invalid-feedback').text(errors.semester[0]);
                        }
                        if(errors.start_date) {
                            $('#start_date').addClass('is-invalid');
                            $('#start_date').siblings('.invalid-feedback').text(errors.start_date[0]);
                        }
                        if(errors.end_date) {
                            $('#end_date').addClass('is-invalid');
                            $('#end_date').siblings('.invalid-feedback').text(errors.end_date[0]);
                        }
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON.message || 'An error occurred',
                            icon: 'error',
                            confirmButtonText: 'OK',
                            zIndex: 9999
                        });
                    }
                },
                complete: function() {
                    // Re-enable submit button
                    submitBtn.prop('disabled', false).text('Create Semester');
                }
            });
        });
    }
</script>