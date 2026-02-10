@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

@section('title', 'Edit Anggota Tim Lab: ' . $teamMember->user->name)

@section('header')
    <x-tabler.page-header :title="'Edit Anggota Tim Lab: ' . $teamMember->user->name" pretitle="Laboratorium">
        <x-slot:actions>
            <x-tabler.button type="back" :href="route('lab.labs.teams.index', $teamMember->encrypted_lab_id)" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-tabler.flash-message />

                    <form action="{{ route('lab.labs.teams.update', [$teamMember->encrypted_lab_id, $teamMember->encrypted_id]) }}" method="POST" class="ajax-form">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="user_id">User</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{ $teamMember->user->name }} ({{ $teamMember->user->email }})" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="jabatan">Jabatan (Opsional)</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan" name="jabatan" value="{{ old('jabatan', $teamMember->jabatan) }}" placeholder="Misal: PIC, Teknisi, dll">
                                @error('jabatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="tanggal_mulai">Tanggal Mulai (Opsional)</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', $teamMember->tanggal_mulai?->format('Y-m-d')) }}">
                                @error('tanggal_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="tanggal_selesai">Tanggal Selesai (Opsional)</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai', $teamMember->tanggal_selesai?->format('Y-m-d')) }}">
                                @error('tanggal_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-10 offset-sm-2">
                                <label class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $teamMember->is_active) ? 'checked' : '' }}>
                                    <span class="form-check-label">Aktif</span>
                                </label>
                                @error('is_active')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-10 offset-sm-2">
                                <x-tabler.button type="submit" text="Simpan" />
                                <x-tabler.button type="cancel" :href="route('lab.labs.teams.index', $teamMember->encrypted_lab_id)" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
