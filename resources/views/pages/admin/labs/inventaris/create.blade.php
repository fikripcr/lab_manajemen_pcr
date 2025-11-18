@extends('layouts.admin.app')

@section('title', 'Tambah Inventaris ke Lab: ' . $lab->name)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Tambah Inventaris ke Lab: {{ $lab->name }}</h4>
                </div>
                <div class="card-body">
                    <x-flash-message />

                    <form action="{{ route('labs.inventaris.store', $lab->encrypted_lab_id) }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="inventaris_id" class="form-label">Nama Alat *</label>
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

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
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
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="no_series" class="form-label">No Series</label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('no_series') is-invalid @enderror" 
                                        id="no_series" 
                                        name="no_series" 
                                        value="{{ old('no_series') }}"
                                        placeholder="Nomor seri atau kode tambahan"
                                    >
                                    @error('no_series')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_penempatan" class="form-label">Tanggal Penempatan</label>
                                    <input 
                                        type="date" 
                                        class="form-control @error('tanggal_penempatan') is-invalid @enderror" 
                                        id="tanggal_penempatan" 
                                        name="tanggal_penempatan" 
                                        value="{{ old('tanggal_penempatan') ?: date('Y-m-d') }}"
                                    >
                                    @error('tanggal_penempatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Keterangan</label>
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
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('labs.inventaris.index', $lab->encrypted_lab_id) }}" class="btn btn-secondary">
                                <i class='bx bx-arrow-back'></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-save'></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
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