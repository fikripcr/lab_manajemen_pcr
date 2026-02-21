@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Unggah Berkas Persyaratan" pretitle="PMB">
    <x-slot:actions>
        <x-tabler.button type="back" href="{{ route('pmb.camaba.dashboard') }}" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Berkas untuk Jalur: {{ $pendaftaran->jalur->nama_jalur }}</h3>
            </div>
            <div class="card-table">
                <x-tabler.datatable-client
                    id="table-upload"
                    :columns="[
                        ['name' => 'Jenis Dokumen'],
                        ['name' => 'Status'],
                        ['name' => 'Aksi', 'className' => 'w-10']
                    ]"
                >
                    @foreach($syarat as $s)
                    @php
                        $upload = $pendaftaran->dokumenUpload->where('jenis_dokumen_id', $s->jenis_dokumen_id)->first();
                    @endphp
                    <tr>
                        <td data-label="Dokumen">
                            <div>{{ $s->jenisDokumen->nama_dokumen }}</div>
                            <div class="text-muted small">
                                {{ $s->is_required ? 'Wajib' : 'Opsional' }} | 
                                Maks: {{ formatBytes($s->jenisDokumen->max_size_kb * 1024) }}
                            </div>
                        </td>
                        <td data-label="Status">
                            @if($upload)
                                <span class="badge bg-success text-white">Sudah Diunggah</span>
                                <div class="small text-muted">{{ formatTanggalIndo($upload->waktu_upload) }}</div>
                            @else
                                <span class="badge bg-secondary text-white">Belum Diunggah</span>
                            @endif
                        </td>
                        <td>
                            <x-tabler.button type="button" class="btn-sm btn-primary ajax-modal-btn" icon="ti ti-upload" text="Unggah"
                                data-modal-target="#modalAction" 
                                data-modal-title="Upload {{ $s->jenisDokumen->nama_dokumen }}" 
                                data-url="{{ route('pmb.camaba.upload-form', ['pendaftaran' => $pendaftaran->encrypted_pendaftaran_id, 'jenis' => $s->jenisDokumen->encrypted_jenis_dokumen_id]) }}" />
                            @if($upload)
                            <x-tabler.button href="{{ asset('storage/' . $upload->file_path) }}" target="_blank" class="btn-sm btn-info" icon="ti ti-eye" text="Lihat" />
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </x-tabler.datatable-client>
            </div>
            @if($pendaftaran->status_terkini == 'Menunggu_Verifikasi_Berkas')
            <div class="card-footer text-end">
                <form action="{{ route('pmb.camaba.finalize-files', $pendaftaran->encrypted_pendaftaran_id) }}" method="POST" class="ajax-form" data-redirect="true">
                    @csrf
                    <x-tabler.button type="success" size="lg" text="Selesai Unggah & Ajukan Verifikasi" />
                </form>
            </div>
            @endif
        </div>
@endsection
