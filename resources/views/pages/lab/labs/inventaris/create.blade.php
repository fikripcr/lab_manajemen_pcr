@extends('layouts.admin.app')

@section('title', 'Tambah Inventaris ke Lab: ' . $lab->name)

@section('header')
    <x-tabler.page-header :title="'Tambah Inventaris ke Lab: ' . $lab->name" pretitle="Laboratorium">
        <x-slot:actions>
            <x-tabler.button type="back" :href="route('lab.labs.inventaris.index', $lab->encrypted_lab_id)" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-tabler.flash-message />

                    <form action="{{ route('lab.labs.inventaris.store', $lab->encrypted_lab_id) }}" method="POST" class="ajax-form">
                        @csrf

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="inventaris_id">Nama Alat</label>
                            <div class="col-sm-10">
                                <select
                                    class="form-select"
                                    id="inventaris_id"
                                    name="inventaris_id"
                                    required
                                    style="width: 100%;"
                                >
                                    @if(old('inventaris_id'))
                                        @php
                                            $selectedInventaris = \App\Models\Inventaris::find(decryptId(old('inventaris_id')));
                                        @endphp
                                        @if($selectedInventaris)
                                            <option value="{{ old('inventaris_id') }}" selected>{{ $selectedInventaris->nama_alat }} ({{ $selectedInventaris->jenis_alat }})</option>
                                        @endif
                                    @endif
                                </select>
                                @error('inventaris_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="no_series">No Series</label>
                            <div class="col-sm-10">
                                <input
                                    type="text"
                                    class="form-control @error('no_series') is-invalid @enderror"
                                    id="no_series"
                                    name="no_series"
                                    value="{{ old('no_series') }}"
                                    placeholder="Nomor seri atau kode tambahan" required
                                >
                                @error('no_series')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="tanggal_penempatan">Tanggal Penempatan</label>
                            <div class="col-sm-10">
                                <input
                                    type="date"
                                    class="form-control @error('tanggal_penempatan') is-invalid @enderror"
                                    id="tanggal_penempatan"
                                    name="tanggal_penempatan"
                                    value="{{ old('tanggal_penempatan') ?: date('Y-m-d') }}" required
                                >
                                @error('tanggal_penempatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="status">Status</label>
                            <div class="col-sm-10">
                                <x-form.select2
                                    id="status"
                                    name="status"
                                    :options="[
                                        'active' => 'Active',
                                        'moved' => 'Moved',
                                        'inactive' => 'Inactive'
                                    ]"
                                    :selected="old('status', 'active')"
                                />
                                @error('status')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="keterangan">Keterangan</label>
                            <div class="col-sm-10">
                                <textarea
                                    class="form-control @error('keterangan') is-invalid @enderror"
                                    id="keterangan"
                                    name="keterangan"
                                    rows="3"
                                    placeholder="Tambahkan keterangan tambahan"
                                >{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-10 offset-sm-2">
                                <x-tabler.button type="submit" text="Simpan" />
                                <x-tabler.button type="cancel" :href="route('lab.labs.inventaris.index', $lab->encrypted_lab_id)" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            if (typeof window.loadSelect2 === 'function') {
                await window.loadSelect2();
                
                $('#inventaris_id').select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Pilih atau ketik untuk mencari inventaris...',
                    allowClear: true,
                    width: '100%',
                    ajax: {
                        url: '{{ route("labs.inventaris.get-inventaris", $lab->encrypted_lab_id) }}',
                        dataType: 'json',
                        delay: 250,
                        data: params => ({
                            search: params.term
                        }),
                        processResults: data => ({
                            results: (data.results || data).map(item => ({
                                id: item.id,
                                text: `${item.text}`
                            }))
                        }),
                        cache: true
                    }
                });
            }
        });
    </script>
@endpush
@endsection

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="inventaris_id">Nama Alat</label>
                            <div class="col-sm-10">
                                <select
                                    class="form-select select2 @error('inventaris_id') is-invalid @enderror"
                                    id="inventaris_id"
                                    name="inventaris_id"
                                    required
                                    style="width: 100%;"
                                >
                                    @if(old('inventaris_id'))
                                        @php
                                            $selectedInventaris = \App\Models\Inventaris::find(decryptId(old('inventaris_id')));
                                        @endphp
                                        @if($selectedInventaris)
                                            <option value="{{ old('inventaris_id') }}" selected>{{ $selectedInventaris->nama_alat }} ({{ $selectedInventaris->jenis_alat }})</option>
                                        @endif
                                    @endif
                                </select>
                                @error('inventaris_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="no_series">No Series</label>
                            <div class="col-sm-10">
                                <input
                                    type="text"
                                    class="form-control @error('no_series') is-invalid @enderror"
                                    id="no_series"
                                    name="no_series"
                                    value="{{ old('no_series') }}"
                                    placeholder="Nomor seri atau kode tambahan" required
                                >
                                @error('no_series')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="tanggal_penempatan">Tanggal Penempatan</label>
                            <div class="col-sm-10">
                                <input
                                    type="date"
                                    class="form-control @error('tanggal_penempatan') is-invalid @enderror"
                                    id="tanggal_penempatan"
                                    name="tanggal_penempatan"
                                    value="{{ old('tanggal_penempatan') ?: date('Y-m-d') }}" required
                                >
                                @error('tanggal_penempatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="status">Status</label>
                            <div class="col-sm-10">
                                <select
                                    class="form-select @error('status') is-invalid @enderror"
                                    id="status"
                                    name="status"
                                >
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="moved" {{ old('status') == 'moved' ? 'selected' : '' }}>Moved</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="keterangan">Keterangan</label>
                            <div class="col-sm-10">
                                <textarea
                                    class="form-control @error('keterangan') is-invalid @enderror"
                                    id="keterangan"
                                    name="keterangan"
                                    rows="3"
                                    placeholder="Tambahkan keterangan tambahan"
                                >{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-10 offset-sm-2">
                                <x-tabler.button type="submit" text="Simpan" />
                                <x-tabler.button type="cancel" :href="route('lab.labs.inventaris.index', $lab->encrypted_lab_id)" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
    <!-- Select2 CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Pilih atau ketik untuk mencari inventaris...',
                allowClear: true,
                ajax: {
                    url: '{{ route("labs.inventaris.get-inventaris", $lab->encrypted_lab_id) }}',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                            search: params.term
                        }),
                    processResults: data => ({
                            results: (data.results || data).map(item => ({
                                id: item.id,
                                text: `${item.text}`
                            }))
                        }),
                    cache: true
                }
            });
        });
    </script>
@endpush
@endsection
