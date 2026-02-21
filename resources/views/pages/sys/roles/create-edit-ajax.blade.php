<x-tabler.form-modal 
    :title="$role->exists ? 'Edit Peran' : 'Tambah Peran Baru'" 
    :route="$role->exists ? route('sys.roles.update', $role->encryptedId) : route('sys.roles.store')" 
    :method="$role->exists ? 'PUT' : 'POST'" 
>
    <x-tabler.form-input 
        name="name" 
        label="Nama Peran" 
        required="true" 
        :value="old('name', $role->name)" 
        placeholder="Contoh: Admin, Operator" 
    />
    
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
