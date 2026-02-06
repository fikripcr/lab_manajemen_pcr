@extends('layouts.admin.app')

@section('title', 'Team Lab: ' . $lab->name)

@section('header')
    <x-sys.page-header :title="'Team Lab: ' . $lab->name" pretitle="Laboratorium">
        <x-slot:actions>
            <x-sys.button type="back" :href="route('labs.index')" />
            <x-sys.button type="create" :href="route('labs.teams.create', $lab->encrypted_lab_id)" text="Create" />
        </x-slot:actions>
    </x-sys.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-tabler.flash-message />

                    <div class="table-responsive">
                        <table class="table card-table table-vcenter table-mobile-md table-nowrap">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Status</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
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
                                                <x-sys.button type="edit" :href="route('labs.teams.edit', [$lab->encrypted_lab_id, $team->encrypted_id])" size="sm" />
                                                <form action="{{ route('labs.teams.destroy', [$lab->encrypted_lab_id, $team->encrypted_id]) }}" method="POST" class="ajax-form-delete d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-sys.button type="delete" size="sm" />
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Tidak ada data anggota tim</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($labTeams->hasPages())
            <div class="card-footer d-flex align-items-center">
                {{ $labTeams->links() }}
            </div>
        @endif
    </div>
@endsection
