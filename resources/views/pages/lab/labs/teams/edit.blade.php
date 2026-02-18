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

                        <x-tabler.form-input name="user_id_display" label="User" value="{{ $teamMember->user->name }} ({{ $teamMember->user->email }})" disabled />

                        <x-tabler.form-input name="jabatan" label="Jabatan (Opsional)" value="{{ $teamMember->jabatan }}" placeholder="Misal: PIC, Teknisi, dll" />

                        <x-tabler.form-input type="date" name="tanggal_mulai" label="Tanggal Mulai (Opsional)" value="{{ $teamMember->tanggal_mulai?->format('Y-m-d') }}" />

                        <x-tabler.form-input type="date" name="tanggal_selesai" label="Tanggal Selesai (Opsional)" value="{{ $teamMember->tanggal_selesai?->format('Y-m-d') }}" />

                        <div class="mb-3">
                            <x-tabler.form-checkbox
                                name="is_active"
                                label="Aktif"
                                value="1"
                                :checked="old('is_active', $teamMember->is_active)"
                                switch
                            />
                            @error('is_active')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
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
