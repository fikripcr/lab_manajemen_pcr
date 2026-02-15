@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.sys.empty' : 'layouts.sys.app')

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

                    selectAllCheckbox.checked = subCategoryCheckboxes.length > 0 && subCategoryCheckboxes.length === checkedSubCategoryCheckboxes.length;
                    selectAllCheckbox.indeterminate = checkedSubCategoryCheckboxes.length > 0 && checkedSubCategoryCheckboxes.length < subCategoryCheckboxes.length;
                });
            }

            // Initialize sub-category select-all checkboxes
            updateSubCategorySelectAllCheckboxes();
        });
    </script>
@endpush
