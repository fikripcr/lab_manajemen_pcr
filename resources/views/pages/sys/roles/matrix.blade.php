@extends('layouts.tabler.app')

@section('title', 'Matriks Hak Akses')

@section('header')
    <x-tabler.page-header title="Matriks Hak Akses" pretitle="System Management">
        <x-slot:actions>
            <x-tabler.button href="{{ route('sys.roles.index') }}" text="Kembali" icon="ti ti-arrow-left" class="btn-outline-secondary shadow-sm" />

            <div class="dropdown d-none d-sm-inline-block">
                <x-tabler.button type="button" class="btn-primary dropdown-toggle shadow-sm" data-bs-toggle="dropdown" aria-expanded="false" icon="ti ti-plus" text="Aksi Tambah" />
                <div class="dropdown-menu dropdown-menu-end shadow-lg" style="border-radius: 12px; border: none;">
                    <a class="dropdown-item ajax-modal-btn py-2" href="javascript:void(0)" data-url="{{ route('sys.roles.create') }}" data-title="Tambah Peran Baru">
                        <i class="ti ti-shield-plus me-2 text-primary"></i> Tambah Role
                    </a>
                    <a class="dropdown-item ajax-modal-btn py-2" href="javascript:void(0)" data-url="{{ route('sys.permissions.create') }}" data-title="Tambah Perizinan Baru">
                        <i class="ti ti-lock-plus me-2 text-azure"></i> Tambah Perizinan
                    </a>
                </div>
            </div>
            
            <div class="dropdown d-none d-sm-inline-block">
                <x-tabler.button type="button" class="btn-azure dropdown-toggle shadow-sm px-3" data-bs-toggle="dropdown" aria-expanded="false" icon="ti ti-category" text="Ganti Sistem" />
                <div class="dropdown-menu dropdown-menu-end shadow-lg" style="min-width: 250px; border-radius: 12px; border: none;">
                    <div class="dropdown-header text-muted small text-uppercase fw-bold p-3">Pilih Modul / Sistem</div>
                    <div class="dropdown-divider m-0"></div>
                    @foreach($allCategories as $category)
                        <a class="dropdown-item d-flex align-items-center py-3 {{ $activeCategory == $category ? 'active' : '' }}" 
                           href="{{ route('sys.roles.matrix', ['system' => $category]) }}">
                            <div class="icon-box me-3 {{ $activeCategory == $category ? 'bg-primary text-white' : 'bg-light text-primary' }} rounded" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                <i class="ti ti-package fs-2"></i>
                            </div>
                            <div>
                                <div class="fw-bold">{{ $category }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header py-3 bg-primary-lt">
                <h3 class="card-title text-primary fw-bold mb-0">
                    <i class="ti ti-layers-intersect me-1"></i> 
                    Sistem: <span class="text-uppercase">{{ $activeCategory }}</span>
                </h3>
            </div>
            
            <form action="{{ route('sys.roles.update-matrix') }}" method="POST" id="matrixForm" class="ajax-form">
                @csrf
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 65vh; overflow-y: auto;">
                        <table class="table table-sm table-vcenter table-nowrap table-bordered table-hover card-table sticky-header mb-0">
                            <thead class="bg-light sticky-top" style="z-index: 20;">
                                <tr>
                                    <th style="min-width: 300px; left: 0; z-index: 35; background-color: #f8fafc !important;" class="sticky-col border-end-heavy">
                                        <div class="text-center text-uppercase small fw-bold">Fitur & Hak Akses</div>
                                    </th>
                                    @foreach($roles as $role)
                                        <th class="text-center" style="min-width: 150px;">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span>{{ $role->name }}</span>
                                                <a href="javascript:void(0)" 
                                                   class="ms-2 text-muted ajax-modal-btn" 
                                                   data-url="{{ route('sys.roles.edit', $role) }}" 
                                                   data-title="Edit Peran: {{ $role->name }}">
                                                    <i class="ti ti-edit fs-3"></i>
                                                </a>
                                            </div>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permissions as $subCategory => $perms)
                                    <tr class="bg-light-lt">
                                        <td class="ps-3 fw-bold sticky-col bg-white border-end-heavy shadow-sm">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="small text-uppercase text-dark">
                                                    <i class="ti ti-subtask me-1"></i> {{ $subCategory }}
                                                </span>
                                                <div class="form-check form-switch m-0" title="Pilih semua di baris ini">
                                                    <input class="form-check-input select-all-sub" 
                                                           type="checkbox" 
                                                           data-subcategory="{{ Str::slug($subCategory) }}">
                                                </div>
                                            </div>
                                        </td>
                                        @foreach($roles as $role)
                                            <td class="text-center bg-light-lt">
                                                <div class="form-check form-switch d-inline-block m-0">
                                                    <input class="form-check-input select-all-role-sub" 
                                                           type="checkbox" 
                                                           data-role="{{ $role->id }}"
                                                           data-subcategory="{{ Str::slug($subCategory) }}"
                                                           title="Pilih semua hak akses untuk role ini di fitur ini">
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                    @foreach($perms as $permission)
                                        <tr>
                                            <td class="ps-4 sticky-col bg-white border-end-heavy shadow-sm">
                                                <div class="fw-bold mb-0" style="font-size: 0.8rem;">
                                                    {{ explode('.', $permission->name)[count(explode('.', $permission->name)) - 1] }}
                                                </div>
                                                <div class="text-muted fst-italic lighter x-small text-truncate" style="max-width: 250px;" title="{{ $permission->description }}">
                                                    {{ $permission->description }}
                                                </div>
                                            </td>
                                            @foreach($roles as $role)
                                                <td class="text-center">
                                                    <label class="form-check form-check-single m-0 d-flex justify-content-center">
                                                        <input class="form-check-input matrix-checkbox" 
                                                               type="checkbox" 
                                                               name="matrix[{{ $role->id }}][]" 
                                                               value="{{ $permission->name }}"
                                                               data-role="{{ $role->id }}"
                                                               data-subcategory="{{ Str::slug($subCategory) }}"
                                                               {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                                    </label>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center bg-light-lt border-top">
                    <div class="text-muted small">
                        <i class="ti ti-info-circle me-1"></i> Centang kotak untuk memberikan hak akses. 
                        <strong>Memory usage optimized (Loaded by system).</strong>
                    </div>
                    <x-tabler.button type="submit" text="Simpan Perubahan Matriks" icon="ti ti-device-floppy" />
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Table Styling */
    .sticky-header table { border-collapse: separate; border-spacing: 0; }
    .sticky-header thead th { position: sticky; top: 0; z-index: 20; background-color: #f8fafc; }
    .sticky-col { position: sticky; left: 0; z-index: 30 !important; background-color: #ffffff !important; opacity: 1 !important; }
    .sticky-header thead th.sticky-col { z-index: 40 !important; }
    
    /* Stronger right border for the sticky column */
    .border-end-heavy {
        border-right: 2.5px solid #000000 !important;
        box-shadow: 4px 0 8px -4px rgba(0,0,0,0.2) !important;
    }

    .x-small { font-size: 0.65rem; }
    .lighter { font-weight: 300; }
    .table-bordered th, .table-bordered td { border-color: #ebedef; padding: 0.5rem 0.75rem !important; }
    .cursor-pointer { cursor: pointer; }
    
    /* Card/UI Refinement */
    .card { border-radius: 8px; overflow: hidden; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.matrix-checkbox');
    const selectAllSubs = document.querySelectorAll('.select-all-sub');
    const selectAllRoleSubs = document.querySelectorAll('.select-all-role-sub');

    // Select All for a whole fiture (SubCategory) - across all roles
    selectAllSubs.forEach(select => {
        select.addEventListener('change', function() {
            const sub = this.dataset.subcategory;
            const isChecked = this.checked;
            document.querySelectorAll(`.matrix-checkbox[data-subcategory="${sub}"]`).forEach(cb => {
                cb.checked = isChecked;
            });
            // Update role-specific master toggles
            document.querySelectorAll(`.select-all-role-sub[data-subcategory="${sub}"]`).forEach(master => {
                master.checked = isChecked;
            });
        });
    });

    // Select All for a specific Role within a Fitur
    selectAllRoleSubs.forEach(select => {
        select.addEventListener('change', function() {
            const sub = this.dataset.subcategory;
            const roleId = this.dataset.role;
            const isChecked = this.checked;
            document.querySelectorAll(`.matrix-checkbox[data-role="${roleId}"][data-subcategory="${sub}"]`).forEach(cb => {
                cb.checked = isChecked;
            });
        });
    });
});
</script>
@endsection
