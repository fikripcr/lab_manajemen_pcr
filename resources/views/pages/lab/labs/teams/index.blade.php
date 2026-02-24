@extends('layouts.tabler.app')

@section('title', 'Team Lab: ' . $lab->name)

@section('header')
    <x-tabler.page-header :title="'Team Lab: ' . $lab->name" pretitle="Laboratorium">
        <x-slot:actions>
            <x-tabler.button type="back" :href="route('lab.labs.index')" />
            <x-tabler.button type="create" class="ajax-modal-btn" :modal-url="route('lab.labs.teams.create', $lab->encrypted_lab_id)" modal-title="Tambah Anggota Tim" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-tabler.flash-message />

                    <x-tabler.datatable-client
                        id="table-lab-teams"
                        :columns="[
                            ['name' => 'Nama'],
                            ['name' => 'Jabatan'],
                            ['name' => 'Tanggal Mulai'],
                            ['name' => 'Status'],
                            ['name' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center']
                        ]"
                    >
                        @forelse($labTeams as $team)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="avatar avatar-sm me-2" style="background-image: url('https://ui-avatars.com/api/?name={{ urlencode($team->user->name) }}&color=7F9CF5&background=EBF4FF')"></span>
                                        <div class="flex-fill">
                                            <div class="font-weight-medium">{{ $team->user->name }}</div>
                                            <div class="text-secondary small">{{ $team->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $team->jabatan ?? '-' }}</td>
                                <td>{{ formatTanggalIndo($team->tanggal_mulai) }}</td>
                                <td>
                                    @if($team->is_active)
                                        <span class="badge bg-success-lt">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary-lt">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <x-tabler.button type="edit" class="ajax-modal-btn" :modal-url="route('lab.labs.teams.edit', [$lab->encrypted_lab_id, $team->encrypted_lab_team_id])" modal-title="Edit Anggota Tim" size="sm" />
                                        <x-tabler.button type="button" class="btn-sm btn-icon btn-outline-danger ajax-delete" data-url="{{ route('lab.labs.teams.destroy', [$lab->encrypted_lab_id, $team->encrypted_lab_team_id]) }}" data-title="Hapus Anggota Tim" icon="bx bx-trash" />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            {{-- x-tabler.datatable-client will handle empty state if needed, but for now we rely on the table structure --}}
                        @endforelse
                    </x-tabler.datatable-client>

                    @if($labTeams->count() === 0)
                        <x-tabler.empty-state
                            title="Tidak ada anggota tim"
                            description="Belum ada anggota tim yang ditambahkan ke laboratorium ini."
                            icon="ti ti-users"
                        />
                    @endif

    </div>
@endsection
