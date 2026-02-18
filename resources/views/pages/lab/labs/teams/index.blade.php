@extends('layouts.admin.app')

@section('title', 'Team Lab: ' . $lab->name)

@section('header')
    <x-tabler.page-header :title="'Team Lab: ' . $lab->name" pretitle="Laboratorium">
        <x-slot:actions>
            <x-tabler.button type="back" :href="route('lab.labs.index')" />
            <x-tabler.button type="create" :href="route('lab.labs.teams.create', $lab->encrypted_lab_id)" />
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
                            ['name' => 'Aksi', 'orderable' => false, 'searchable' => false, 'className' => 'text-end']
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
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <x-tabler.button type="edit" :href="route('lab.labs.teams.edit', [$lab->encrypted_lab_id, $team->encrypted_id])" size="sm" />
                                        <form action="{{ route('lab.labs.teams.destroy', [$lab->encrypted_lab_id, $team->encrypted_id]) }}" method="POST" class="ajax-form-delete d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <x-tabler.button type="delete" size="sm" />
                                        </form>
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
