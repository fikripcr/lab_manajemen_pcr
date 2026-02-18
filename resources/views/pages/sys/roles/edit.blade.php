@if(request()->ajax() || request()->has('ajax'))
    <x-tabler.form-modal
        title="Ubah Role: {{ $role->name }}"
        route="{{ route('sys.roles.update', $role->encryptedId) }}"
        method="PUT"
        submitText="Simpan Perubahan"
        submitIcon="ti-device-floppy"
    >
        {{-- Role Form Card --}}
        <div class="mb-3">
            <x-tabler.form-input name="name" label="Role Name" id="name" value="{{ old('name', $role->name) }}" required="true" />
        </div>

        {{-- Permissions Section --}}
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="form-label mb-0">Permissions</h5>
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary" id="selectAllBtnAjax">Pilih Semua</button>
                    <button type="button" class="btn btn-outline-secondary" id="deselectAllBtnAjax">Hapus Semua</button>
                </div>
            </div>

            <div class="accordion" id="permissionsAccordion">
                @php
                    $categorizedPermissions = collect($permissions)->groupBy(['category', 'sub_category']);
                @endphp

                @foreach ($categorizedPermissions as $category => $subCategories)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ Str::slug($category) }}">
                            <button class="accordion-button collapsed py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ Str::slug($category) }}" aria-expanded="false" aria-controls="collapse{{ Str::slug($category) }}">
                                {{ $category }}
                            </button>
                        </h2>
                        <div id="collapse{{ Str::slug($category) }}" class="accordion-collapse collapse" aria-labelledby="heading{{ Str::slug($category) }}">
                            <div class="accordion-body p-2">
                                @foreach ($subCategories as $subCategory => $perms)
                                    <div class="mb-3 border-bottom pb-2 last-no-border">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0 fw-bold small text-uppercase text-muted">{{ $subCategory }}</h6>
                                            <div class="form-check form-check-inline form-switch m-0">
                                                <input class="form-check-input sub-category-select-all" type="checkbox" role="switch" id="selectAll_{{ Str::slug($category . '_' . $subCategory) }}" data-subcategory="{{ Str::slug($category . '_' . $subCategory) }}">
                                            </div>
                                        </div>
                                        <div class="row g-2">
                                            @foreach ($perms as $permission)
                                                <div class="col-6">
                                                    <label class="form-check form-check-inline m-0">
                                                        <input class="form-check-input permission-checkbox" type="checkbox" 
                                                            name="permissions[]" 
                                                            value="{{ $permission->name }}"
                                                            data-subcategory="{{ Str::slug($category . '_' . $subCategory) }}"
                                                            {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}
                                                        >
                                                        <span class="form-check-label small">{{ Str::slug($permission->name, '_') }}</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <script>
            (function() {
                const selectAllBtn = document.getElementById('selectAllBtnAjax');
                const deselectAllBtn = document.getElementById('deselectAllBtnAjax');
                const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
                const subCategorySelectAllCheckboxes = document.querySelectorAll('.sub-category-select-all');

                function updateSubCategorySelectAllCheckboxes() {
                    subCategorySelectAllCheckboxes.forEach(selectAllCheckbox => {
                        const subCategory = selectAllCheckbox.getAttribute('data-subcategory');
                        const subCategoryCheckboxes = document.querySelectorAll(`.permission-checkbox[data-subcategory="${subCategory}"]`);
                        const checkedSubCategoryCheckboxes = document.querySelectorAll(`.permission-checkbox[data-subcategory="${subCategory}"]:checked`);

                        selectAllCheckbox.checked = subCategoryCheckboxes.length > 0 && subCategoryCheckboxes.length === checkedSubCategoryCheckboxes.length;
                        selectAllCheckbox.indeterminate = checkedSubCategoryCheckboxes.length > 0 && checkedSubCategoryCheckboxes.length < subCategoryCheckboxes.length;
                    });
                }

                // Initial update
                updateSubCategorySelectAllCheckboxes();

                // Select/Deselect All
                if(selectAllBtn && deselectAllBtn) {
                    selectAllBtn.addEventListener('click', function() {
                        permissionCheckboxes.forEach(checkbox => checkbox.checked = true);
                        updateSubCategorySelectAllCheckboxes();
                    });

                    deselectAllBtn.addEventListener('click', function() {
                        permissionCheckboxes.forEach(checkbox => checkbox.checked = false);
                        updateSubCategorySelectAllCheckboxes();
                    });
                }

                // Sub-category Select All
                subCategorySelectAllCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const subCategory = this.getAttribute('data-subcategory');
                        const isChecked = this.checked;
                        document.querySelectorAll(`.permission-checkbox[data-subcategory="${subCategory}"]`)
                            .forEach(pc => pc.checked = isChecked);
                    });
                });

                // Individual Checkbox Change
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', updateSubCategorySelectAllCheckboxes);
                });
            })();
        </script>
    </x-tabler.form-modal>
@else
    @extends('layouts.tabler.app')

    @section('title', 'Edit Role')

    @section('header')
        <x-tabler.page-header title="Ubah Role" pretitle="Access Control">
            <x-slot:actions>
                <x-tabler.button type="back" :href="route('sys.roles.index')" />
            </x-slot:actions>
        </x-tabler.page-header>
    @endsection

    @section('content')
        <div class="row">
            <div class="col-12">
                <x-tabler.flash-message />
                <form class="ajax-form" action="{{ route('sys.roles.update', $role->encryptedId) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Role Form Card --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="mb-3">
                                <x-tabler.form-input name="name" label="Role Name" id="name" value="{{ old('name', $role->name) }}" required="true" />
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Permissions Cards --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="form-label">Permissions</h5>
                            <div>
                                <x-tabler.button type="button" class="btn-sm btn-outline-primary" id="selectAllBtn" icon="bx bx-check-square" text="Pilih Semua" />
                                <x-tabler.button type="button" class="btn-sm btn-outline-secondary ms-1" id="deselectAllBtn" icon="bx bx-square" text="Hapus Semua" />
                            </div>
                        </div>

                        {{-- Group permissions by category and sub-category --}}
                        @php
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
                                                                <x-tabler.form-checkbox
                                                                    name="permissions[]"
                                                                    value="{{ $permission->name }}"
                                                                    id="perm_{{ $permission->id }}"
                                                                    input-class="permission-checkbox"
                                                                    data-subcategory="{{ Str::slug($category . '_' . $subCategory) }}"
                                                                    :checked="in_array($permission->name, $rolePermissions)"
                                                                >
                                                                    <span class="small">{{ Str::slug($permission->name, '_') }}</span>
                                                                </x-tabler.form-checkbox>
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

                    <div class="d-flex justify-content-start gap-2">
                        <x-tabler.button type="submit" />
                        <x-tabler.button type="back" :href="route('sys.roles.index')" />
                    </div>
                </form>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const selectAllBtn = document.getElementById('selectAllBtn');
                const deselectAllBtn = document.getElementById('deselectAllBtn');
                const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
                const subCategorySelectAllCheckboxes = document.querySelectorAll('.sub-category-select-all');

                // Select/Deselect All permissions
                if(selectAllBtn) {
                     selectAllBtn.addEventListener('click', function() {
                        permissionCheckboxes.forEach(checkbox => checkbox.checked = true);
                        updateSubCategorySelectAllCheckboxes();
                    });
                }
               
                if(deselectAllBtn) {
                     deselectAllBtn.addEventListener('click', function() {
                        permissionCheckboxes.forEach(checkbox => checkbox.checked = false);
                        updateSubCategorySelectAllCheckboxes();
                    });
                }

                // Sub-category Select All functionality
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

                // Update sub-category select-all checkboxes based on individual permissions
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', updateSubCategorySelectAllCheckboxes);
                });

                function updateSubCategorySelectAllCheckboxes() {
                    subCategorySelectAllCheckboxes.forEach(selectAllCheckbox => {
                        const subCategory = selectAllCheckbox.getAttribute('data-subcategory');
                        const subCategoryCheckboxes = document.querySelectorAll(`.permission-checkbox[data-subcategory="${subCategory}"]`);
                        const checkedSubCategoryCheckboxes = document.querySelectorAll(`.permission-checkbox[data-subcategory="${subCategory}"]:checked`);

                        if(selectAllCheckbox) {
                             selectAllCheckbox.checked = subCategoryCheckboxes.length > 0 && subCategoryCheckboxes.length === checkedSubCategoryCheckboxes.length;
                             selectAllCheckbox.indeterminate = checkedSubCategoryCheckboxes.length > 0 && checkedSubCategoryCheckboxes.length < subCategoryCheckboxes.length;
                        }
                    });
                }

                // Initialize sub-category select-all checkboxes
                updateSubCategorySelectAllCheckboxes();
            });
        </script>
    @endpush
@endif
