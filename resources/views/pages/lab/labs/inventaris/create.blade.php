@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

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
                                <x-tabler.form-select name="inventaris_id" id="inventaris_id" required class="mb-0" style="width: 100%;">
                                    @if(old('inventaris_id'))
                                        @php
                                            $selectedInventaris = \App\Models\Inventaris::find(decryptId(old('inventaris_id')));
                                        @endphp
                                        @if($selectedInventaris)
                                            <option value="{{ old('inventaris_id') }}" selected>{{ $selectedInventaris->nama_alat }} ({{ $selectedInventaris->jenis_alat }})</option>
                                        @endif
                                    @endif
                                </x-tabler.form-select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="no_series">No Series</label>
                            <div class="col-sm-10">
                                <x-tabler.form-input name="no_series" placeholder="Nomor seri atau kode tambahan" required class="mb-0" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="tanggal_penempatan">Tanggal Penempatan</label>
                            <div class="col-sm-10">
                                <x-tabler.form-input type="date" name="tanggal_penempatan" value="{{ date('Y-m-d') }}" required class="mb-0" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="status">Status</label>
                            <div class="col-sm-10">
                                <x-tabler.form-select name="status" :options="['active' => 'Active', 'moved' => 'Moved', 'inactive' => 'Inactive']" selected="active" class="mb-0" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="keterangan">Keterangan</label>
                            <div class="col-sm-10">
                                <x-tabler.form-textarea name="keterangan" rows="3" placeholder="Tambahkan keterangan tambahan" class="mb-0" />
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
