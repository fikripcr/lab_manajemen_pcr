@extends('layouts.admin.app')

@section('title', 'Tambah Anggota Tim Lab: ' . $lab->name)

@section('header')
    <x-sys.page-header :title="'Tambah Anggota Tim Lab: ' . $lab->name" pretitle="Laboratorium">
        <x-slot:actions>
            <x-sys.button type="back" :href="route('labs.teams.index', $lab->encrypted_lab_id)" />
        </x-slot:actions>
    </x-sys.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-tabler.flash-message />

                    <form action="{{ route('labs.teams.store', $lab->encrypted_lab_id) }}" method="POST" class="ajax-form">
                        @csrf

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="user_id">Pilih User</label>
                            <div class="col-sm-10">
                                <select class="form-select select2 @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required style="width: 100%;">
                                    @if(old('user_id'))
                                        @php
                                            $selectedUser = \App\Models\User::find(decryptId(old('user_id')));
                                        @endphp
                                        @if($selectedUser)
                                            <option value="{{ old('user_id') }}" selected>{{ $selectedUser->name }} ({{ $selectedUser->email }})</option>
                                        @endif
                                    @endif
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="jabatan">Jabatan (Opsional)</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan" name="jabatan" value="{{ old('jabatan') }}" placeholder="Misal: PIC, Teknisi, dll">
                                @error('jabatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="tanggal_mulai">Tanggal Mulai (Opsional)</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}">
                                @error('tanggal_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-10 offset-sm-2">
                                <x-sys.button type="submit" text="Simpan" />
                                <x-sys.button type="cancel" :href="route('labs.teams.index', $lab->encrypted_lab_id)" />
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
                placeholder: 'Pilih atau ketik untuk mencari user...',
                allowClear: true,
                ajax: {
                    url: '{{ route("labs.teams.get-users", $lab->encrypted_lab_id) }}',
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
