@extends('layouts.admin.app')
@section('title', 'Tambah Sasaran Kinerja')

@section('header')
<x-tabler.page-header title="Tambah Sasaran Kinerja" pretitle="KPI">
    <x-slot:actions>
        <x-tabler.button type="a" href="{{ route('pemutu.kpi.index') }}" icon="ti ti-arrow-left" text="Kembali" class="btn-secondary" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-11">
        <form action="{{ route('pemutu.kpi.store') }}" method="POST" class="card ajax-form">
            @csrf
            
            <div class="card-header">
                <h3 class="card-title">Form Input Sasaran Kinerja (Bulk)</h3>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-4">
                        <x-tabler.form-select 
                            name="parent_id" 
                            label="Indikator Standar (Induk)" 
                            type="select2" 
                            :options="$parents->mapWithKeys(function($p) {
                                return [$p->indikator_id => '[' . $p->no_indikator . '] ' . Str::limit($p->indikator, 150)];
                            })->toArray()"
                            :selected="old('parent_id')" 
                            placeholder="Cari indikator standar..." 
                            required="true" 
                        />
                        <div class="form-hint">Pilih Indikator Standar yang menjadi acuan untuk sasaran kinerja ini. Nomor/Kode akan dibuat otomatis.</div>
                    </div>
                </div>

                <div class="hr-text text-purple">Daftar Sasaran Kinerja</div>

                <div class="table-responsive">
                    <table class="table table-vcenter table-nowrap card-table">
                        <thead>
                            <tr>
                                <th width="25%">Sasaran Kinerja <span class="text-danger">*</span></th>
                                <th width="25%">Unit Kerja / Penanggung Jawab</th>
                                <th width="15%">Target & Satuan</th>
                                <th width="35%">Keterangan</th>
                                <th width="50px"></th>
                            </tr>
                        </thead>
                        <tbody id="kpi-items-body">
                            {{-- Rows will be added dynamically --}}
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <button type="button" class="btn btn-ghost-primary" id="add-item-btn">
                        <i class="ti ti-plus me-1"></i> Tambah Sasaran
                    </button>
                </div>
            </div>

            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">Simpan Semua Sasaran</button>
            </div>
        </form>
    </div>
</div>

{{-- Row Template --}}
<template id="kpi-row-template">
    <tr class="kpi-item-row">
        <td class="align-top">
            <textarea name="items[INDEX][indikator]" class="form-control" rows="4" placeholder="Nama Sasaran..." required></textarea>
        </td>
        <td class="align-top">
            <select name="items[INDEX][org_unit_ids][]" class="form-control select2-org-INDEX" multiple="multiple" style="width: 100%">
                @foreach($orgUnits as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
            <div class="form-hint small mt-1">Pilih satu atau lebih unit kerja.</div>
        </td>
        <td class="align-top">
            <div class="mb-2">
                <input type="text" name="items[INDEX][target]" class="form-control form-control-sm" placeholder="Nilai Target">
                <div class="form-hint small mt-1">Nilai Target</div>
            </div>
            <div>
                <input type="text" name="items[INDEX][unit_ukuran]" class="form-control form-control-sm" placeholder="%, org, dll">
                <div class="form-hint small mt-1">Satuan</div>
            </div>
        </td>
        <td class="align-top">
            <textarea name="items[INDEX][keterangan]" id="editor-INDEX" class="form-control kpi-editor" placeholder="Keterangan..."></textarea>
        </td>
        <td class="align-top text-end">
            <button type="button" class="btn btn-icon btn-ghost-danger remove-item-btn" title="Hapus">
                <i class="ti ti-trash"></i>
            </button>
        </td>
    </tr>
</template>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const body = document.getElementById('kpi-items-body');
        const template = document.getElementById('kpi-row-template').innerHTML;
        const addBtn = document.getElementById('add-item-btn');
        let rowIndex = 0;

        async function addRow() {
            const html = template.replace(/INDEX/g, rowIndex);
            body.insertAdjacentHTML('beforeend', html);
            
            const currentEditorId = 'editor-' + rowIndex;
            const currentSelectClass = '.select2-org-' + rowIndex;
            
            // Re-initialize Select2 if available
            if (window.loadSelect2) {
                await window.loadSelect2();
                $(currentSelectClass).select2({
                    theme: 'bootstrap-5',
                    placeholder: "Pilih unit...",
                    allowClear: true,
                    dropdownParent: $(currentSelectClass).parent()
                });
            }

            // Initialize HugeRTE for the row
            if (window.loadHugeRTE) {
                window.loadHugeRTE('#' + currentEditorId, {
                    height: 150,
                    menubar: false,
                    statusbar: false,
                    plugins: 'lists',
                    toolbar: 'bold italic | bullist numlist',
                    content_style: 'body { font-size: 14px; }',
                    setup: function (editor) {
                        editor.on('change', function () {
                            editor.save();
                        });
                    }
                });
            }
            
            rowIndex++;
        }

        addBtn.addEventListener('click', addRow);

        // Delegation for remove button
        body.addEventListener('click', function(e) {
            const btn = e.target.closest('.remove-item-btn');
            if (btn) {
                const row = btn.closest('tr');
                const editorId = row.querySelector('.kpi-editor').id;
                const select = row.querySelector('select');
                
                if (window.hugerte && window.hugerte.get(editorId)) {
                    window.hugerte.get(editorId).remove();
                }

                if ($(select).data('select2')) {
                    $(select).select2('destroy');
                }
                
                row.remove();
            }
        });

        // Add first row by default
        addRow();
    });
</script>
@endpush
