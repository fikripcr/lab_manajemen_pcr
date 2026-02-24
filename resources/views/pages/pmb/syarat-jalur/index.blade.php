@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Syarat Dokumen: {{ $jalur->nama_jalur }}" pretitle="Pengaturan Jalur">
    <x-slot:actions>
        <x-tabler.button type="back" href="{{ route('pmb.jalur.index') }}" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')

        <div class="row row-cards">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Tambah Syarat</h3></div>
                    <div class="card-body">
                        <form action="{{ route('pmb.syarat-jalur.store') }}" method="POST" class="ajax-form" data-redirect="true">
                            @csrf
                            <input type="hidden" name="jalur_id" value="{{ $jalur->encrypted_jalur_id }}">

                            <div class="mb-3">
                                <label class="form-label required">Jenis Dokumen</label>
                                <select name="jenis_dokumen_id" class="form-select" required>
                                    <option value="">-- Pilih Dokumen --</option>
                                    @foreach($jenisDokumen as $jd)
                                        <option value="{{ $jd->encrypted_jenis_dokumen_id }}">{{ $jd->nama_dokumen }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <x-tabler.form-checkbox name="is_required" label="Wajib Diupload" checked="true" />

                            <div class="form-footer mt-3">
                                <x-tabler.button type="submit" class="w-100" text="Tambah Syarat" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Daftar Syarat Dokumen</h3></div>
                    <x-tabler.datatable-client
                        id="table-syarat"
                        :columns="[
                            ['name' => 'No'],
                            ['name' => 'Nama Dokumen'],
                            ['name' => 'Tipe/Ukuran'],
                            ['name' => 'Wajib'],
                            ['name' => '', 'orderable' => false, 'searchable' => false, 'class' => 'w-1']
                        ]"
                    >
                        @forelse($syarat as $s)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $s->jenisDokumen->nama_dokumen }}</td>
                            <td class="text-muted">
                                {{ $s->jenisDokumen->tipe_file ?? '*' }} / {{ formatBytes($s->jenisDokumen->max_size_kb * 1024) }}
                            </td>
                            <td>
                                @if($s->is_required)
                                    <span class="badge bg-danger text-white">Wajib</span>
                                @else
                                    <span class="badge bg-secondary text-white">Opsional</span>
                                @endif
                            </td>
                            <td>
                                <x-tabler.button type="button" class="btn-sm btn-icon btn-danger ajax-delete"
                                    data-url="{{ route('pmb.syarat-jalur.destroy', $s->encrypted_syaratdokumenjalur_id) }}" data-title="Hapus Syarat?" icon="ti ti-trash" />
                            </td>
                        </tr>
                        @empty
                            {{-- x-tabler.datatable-client handles empty state --}}
                        @endforelse
                    </x-tabler.datatable-client>

                    @if($syarat->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <div class="mb-2"><i class="ti ti-file-off ti-lg text-secondary"></i></div>
                            Belum ada syarat dokumen.
                        </div>
                    @endif
                </div>
            </div>
        </div>
@endsection
