@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

@section('title', 'Tambah Anggota Tim Lab: ' . $lab->name)

@section('header')
    <x-tabler.page-header :title="'Tambah Anggota Tim Lab: ' . $lab->name" pretitle="Laboratorium">
        <x-slot:actions>
            <x-tabler.button type="back" :href="route('lab.labs.teams.index', $lab->encrypted_lab_id)" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-tabler.flash-message />

                    <form action="{{ route('lab.labs.teams.store', $lab->encrypted_lab_id) }}" method="POST" class="ajax-form">
                        @csrf

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="user_id">Pilih User</label>
                            <div class="col-sm-10">
                                <x-tabler.form-select name="user_id" id="user_id" required class="mb-0" style="width: 100%;">
                                    @if(old('user_id'))
                                        @php
                                            $selectedUser = \App\Models\User::find(decryptId(old('user_id')));
                                        @endphp
                                        @if($selectedUser)
                                            <option value="{{ old('user_id') }}" selected>{{ $selectedUser->name }} ({{ $selectedUser->email }})</option>
                                        @endif
                                    @endif
                                </x-tabler.form-select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-10 offset-sm-2">
                                <x-tabler.form-input name="jabatan" label="Jabatan (Opsional)" placeholder="Misal: PIC, Teknisi, dll" class="mb-0" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-10 offset-sm-2">
                                <x-tabler.form-input type="date" name="tanggal_mulai" label="Tanggal Mulai (Opsional)" class="mb-0" />
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-10 offset-sm-2">
                                <x-tabler.button type="submit" text="Simpan" />
                                <x-tabler.button type="cancel" :href="route('lab.labs.teams.index', $lab->encrypted_lab_id)" />
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
                
                $('#user_id').select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Pilih atau ketik untuk mencari user...',
                    allowClear: true,
                    width: '100%',
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
            }
        });
    </script>
@endpush
@endsection

