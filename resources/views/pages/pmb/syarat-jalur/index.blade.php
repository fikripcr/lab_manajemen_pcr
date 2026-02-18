@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Syarat Dokumen: {{ $jalur->nama_jalur }}" pretitle="Pengaturan Jalur">
    <x-slot:actions>
        <x-tabler.button href="{{ route('pmb.jalur.index') }}" class="btn-outline-secondary" icon="ti ti-arrow-left" text="Kembali" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Tambah Syarat</h3></div>
                    <div class="card-body">
                        <form action="{{ route('pmb.syarat-jalur.store') }}" method="POST" class="ajax-form" data-redirect="true">
                            @csrf
                            <input type="hidden" name="jalur_id" value="{{ $jalur->encrypted_id }}">
                            
                            <div class="mb-3">
                                <label class="form-label required">Jenis Dokumen</label>
                                <select name="jenis_dokumen_id" class="form-select" required>
                                    <option value="">-- Pilih Dokumen --</option>
                                    @foreach($jenisDokumen as $jd)
                                        <option value="{{ $jd->encrypted_id }}">{{ $jd->nama_dokumen }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <x-tabler.form-checkbox name="is_required" label="Wajib Diupload" checked="true" />

                            <div class="form-footer mt-3">
                                <x-tabler.button type="submit" class="btn-primary w-100" text="Tambah Syarat" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Daftar Syarat Dokumen</h3></div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Dokumen</th>
                                    <th>Tipe/Ukuran</th>
                                    <th>Wajib</th>
                                    <th class="w-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($syarat as $s)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $s->jenisDokumen->nama_dokumen }}</td>
                                    <td class="text-muted">
                                        {{ $s->jenisDokumen->tipe_file ?? '*' }} / {{ formatBytes($s->jenisDokumen->max_size_kb * 1024) }}
                                    </td>
                                    <td>
                                        @if($s->is_required)
                                            <span class="badge bg-danger">Wajib</span>
                                        @else
                                            <span class="badge bg-secondary text-white">Opsional</span>
                                        @endif
                                    </td>
                                    <td>
                                        <x-tabler.button type="button" class="btn-sm btn-icon btn-danger ajax-delete" 
                                            data-url="{{ route('pmb.syarat-jalur.destroy', $s->encrypted_id) }}" data-title="Hapus Syarat?" icon="ti ti-trash" />
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada syarat dokumen.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
