<form class="ajax-form" action="{{ route('sys.roles.update', $role->encryptedId) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Edit Role: {{ $role->name }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">
        
        <!-- Role Name -->
        <div class="mb-4">
            <label for="name" class="form-label">Role Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $role->name) }}">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Permissions -->
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="form-label mb-0">Permissions</h5>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllBtn">
                        <i class="bx bx-check-square"></i> Select All
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary ms-1" id="deselectAllBtn">
                        <i class="bx bx-square"></i> Deselect All
                    </button>
                </div>
            </div>

            @php
                $categorizedPermissions = collect($permissions)->groupBy(['category', 'sub_category']);
            @endphp

            @foreach ($categorizedPermissions as $category => $subCategories)
                <div class="mb-4">
                    <h6 class="mb-2 text-muted fw-bold">{{ $category }}</h6>
                    <div class="accordion" id="accordion{{ Str::slug($category) }}">
                        @foreach ($subCategories as $subCategory => $perms)
                            <div class="accordion-item border-0 shadow-none mb-2">
                                <h2 class="accordion-header" id="heading{{ Str::slug($category . '_' . $subCategory) }}">
                                    <button class="accordion-button collapsed py-2 bg-lighter" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ Str::slug($category . '_' . $subCategory) }}" aria-expanded="false" aria-controls="collapse{{ Str::slug($category . '_' . $subCategory) }}">
                                        <div class="d-flex align-items-center w-100 me-3">
                                            <span class="me-auto">{{ $subCategory }}</span>
                                            <div class="form-check form-switch me-3 mb-0" onclick="event.stopPropagation()">
                                                <input class="form-check-input sub-category-select-all" type="checkbox" role="switch" id="selectAll_{{ Str::slug($category . '_' . $subCategory) }}" data-subcategory="{{ Str::slug($category . '_' . $subCategory) }}">
                                                <label class="form-check-label" for="selectAll_{{ Str::slug($category . '_' . $subCategory) }}">All</label>
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse{{ Str::slug($category . '_' . $subCategory) }}" class="accordion-collapse collapse" aria-labelledby="heading{{ Str::slug($category . '_' . $subCategory) }}">
                                    <div class="accordion-body pt-2 pb-0">
                                        <div class="row">
                                            @foreach ($perms as $permission)
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input permission-checkbox" type="checkbox" value="{{ $permission->name }}" id="perm_{{ $permission->id }}" name="permissions[]" data-subcategory="{{ Str::slug($category . '_' . $subCategory) }}" {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                                        <label class="form-check-label small cursor-pointer" for="perm_{{ $permission->id }}">
                                                            {{ Str::slug($permission->name, '_') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="modal-footer">
        <x-sys.button type="cancel" data-bs-dismiss="modal" />
        <x-sys.button type="submit" />
    </div>
</form>

<script>
    (function() {
        const selectAllBtn = document.getElementById('selectAllBtn');
        const deselectAllBtn = document.getElementById('deselectAllBtn');
        const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
        const subCategorySelectAllCheckboxes = document.querySelectorAll('.sub-category-select-all');

        if (selectAllBtn) {
            selectAllBtn.addEventListener('click', function() {
                permissionCheckboxes.forEach(checkbox => checkbox.checked = true);
                updateSubCategorySelectAllCheckboxes();
            });
        }

        if (deselectAllBtn) {
            deselectAllBtn.addEventListener('click', function() {
                permissionCheckboxes.forEach(checkbox => checkbox.checked = false);
                updateSubCategorySelectAllCheckboxes();
            });
        }

        if (subCategorySelectAllCheckboxes) {
            subCategorySelectAllCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const subCategory = this.getAttribute('data-subcategory');
                    const isChecked = this.checked;

                    document.querySelectorAll(`.permission-checkbox[data-subcategory="${subCategory}"]`)
                        .forEach(permissionCheckbox => {
                            permissionCheckbox.checked = isChecked;
                        });
                });
            });
        }

        if (permissionCheckboxes) {
            permissionCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSubCategorySelectAllCheckboxes);
            });
        }

        function updateSubCategorySelectAllCheckboxes() {
            subCategorySelectAllCheckboxes.forEach(selectAllCheckbox => {
                const subCategory = selectAllCheckbox.getAttribute('data-subcategory');
                const subCategoryCheckboxes = document.querySelectorAll(`.permission-checkbox[data-subcategory="${subCategory}"]`);
                const checkedSubCategoryCheckboxes = document.querySelectorAll(`.permission-checkbox[data-subcategory="${subCategory}"]:checked`);

                selectAllCheckbox.checked = subCategoryCheckboxes.length > 0 && subCategoryCheckboxes.length === checkedSubCategoryCheckboxes.length;
                selectAllCheckbox.indeterminate = checkedSubCategoryCheckboxes.length > 0 && checkedSubCategoryCheckboxes.length < subCategoryCheckboxes.length;
            });
        }

        // Initialize state
        updateSubCategorySelectAllCheckboxes();
    })();
</script>

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom">
        <h4 class="fw-bold py-3 mb-0">Edit Role: {{ $role->name }}</h4>
        <div class="d-flex gap-2">
            <x-sys.button type="back" :href="route('sys.roles.index')" />
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <x-sys.flash-message />
            <form class="ajax-form" action="{{ route('sys.roles.update', $role->encryptedId) }}" method="POST">

                    <!-- Role Form Card -->
                        <div class="card mb-4">
                            <div class="card-body">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="name" class="form-label">Role Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $role->name) }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Permissions Cards -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="form-label">Permissions</h5>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllBtn">
                                        <i class="bx bx-check-square"></i> Select All
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary ms-1" id="deselectAllBtn">
                                        <i class="bx bx-square"></i> Deselect All
                                    </button>
                                </div>
                            </div>

                            <!-- Group permissions by category and sub-category -->
                            @php
                                // Group all permissions by category and sub-category
                                $categorizedPermissions = collect($permissions)->groupBy(['category', 'sub_category']);
                            @endphp

                            @foreach ($categorizedPermissions as $category => $subCategories)
                                <div class="mb-4">
                                    <h6 class="mb-3 text-muted">{{ $category }}</h6>
                                    <div class="row">
                                        @foreach ($subCategories as $subCategory => $perms)
                                            <div class="col-md-3 mb-4">
                                                <div class="card h-100">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <h6 class="mb-0">{{ $subCategory }}</h6>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input sub-category-select-all" type="checkbox" role="switch" id="selectAll_{{ Str::slug($category . '_' . $subCategory) }}" data-subcategory="{{ Str::slug($category . '_' . $subCategory) }}">
                                                            <label class="form-check-label" for="selectAll_{{ Str::slug($category . '_' . $subCategory) }}">
                                                                All
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            @foreach ($perms as $permission)
                                                                <div class="col-md-12 mb-1">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input permission-checkbox @error('permissions') is-invalid @enderror" type="checkbox" value="{{ $permission->name }}" id="perm_{{ $permission->id }}" name="permissions[]" data-subcategory="{{ Str::slug($category . '_' . $subCategory) }}" {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                                                        <label class="form-check-label small" for="perm_{{ $permission->id }}">
                                                                            {{ Str::slug($permission->name, '_') }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            @error('permissions')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        @push('scripts')
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const selectAllBtn = document.getElementById('selectAllBtn');
                                    const deselectAllBtn = document.getElementById('deselectAllBtn');
                                    const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
                                    const subCategorySelectAllCheckboxes = document.querySelectorAll('.sub-category-select-all');

                                    // Select/Deselect All permissions
                                    selectAllBtn.addEventListener('click', function() {
                                        permissionCheckboxes.forEach(checkbox => checkbox.checked = true);
                                        updateSubCategorySelectAllCheckboxes();
                                    });

                                    deselectAllBtn.addEventListener('click', function() {
                                        permissionCheckboxes.forEach(checkbox => checkbox.checked = false);
                                        updateSubCategorySelectAllCheckboxes();
                                    });

                                    // Sub-category Select All functionality
                                    subCategorySelectAllCheckboxes.forEach(checkbox => {
                                        checkbox.addEventListener('change', function() {
                                            const subCategory = this.getAttribute('data-subcategory');
                                            const isChecked = this.checked;

                                            // Find all permission checkboxes within this sub-category
                                            document.querySelectorAll(`.permission-checkbox[data-subcategory="${subCategory}"]`)
                                                .forEach(permissionCheckbox => {
                                                    permissionCheckbox.checked = isChecked;
                                                });
                                        });
                                    });

                                    // Update sub-category select-all checkboxes based on individual permissions
                                    permissionCheckboxes.forEach(checkbox => {
                                        checkbox.addEventListener('change', updateSubCategorySelectAllCheckboxes);
                                    });

                                    function updateSubCategorySelectAllCheckboxes() {
                                        // Update each sub-category select-all checkbox
                                        subCategorySelectAllCheckboxes.forEach(selectAllCheckbox => {
                                            const subCategory = selectAllCheckbox.getAttribute('data-subcategory');

                                            const subCategoryCheckboxes = document.querySelectorAll(`.permission-checkbox[data-subcategory="${subCategory}"]`);
                                            const checkedSubCategoryCheckboxes = document.querySelectorAll(`.permission-checkbox[data-subcategory="${subCategory}"]:checked`);

                                            selectAllCheckbox.checked = subCategoryCheckboxes.length === checkedSubCategoryCheckboxes.length;
                                            selectAllCheckbox.indeterminate = checkedSubCategoryCheckboxes.length > 0 && checkedSubCategoryCheckboxes.length < subCategoryCheckboxes.length;
                                        });
                                    }

                                    // Initialize sub-category select-all checkboxes
                                    updateSubCategorySelectAllCheckboxes();
                                });
                            </script>
                        @endpush

                        <div class="d-flex justify-content-start gap-2">
                            <x-sys.button type="submit" />
                            <x-sys.button type="back" :href="route('sys.roles.index')" />
                        </div>
            </form>
        </div>
    </div>
@endsection
