@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Komposisi Paket: {{ $paket->nama_paket }}" pretitle="CBT Engine">
    <x-slot:actions>
        <x-tabler.button href="{{ route('cbt.paket.index') }}" class="btn-outline-secondary" icon="ti ti-arrow-left" text="Kembali" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            {{-- Soal yang sudah ada dalam paket --}}
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Soal Terpilih ({{ $paket->komposisi->count() }})</h3>
                    </div>
                    <div class="card-table">
                        <x-tabler.datatable-client
                            id="table-soal-terpilih"
                            :columns="[
                                ['name' => 'No', 'sortable' => true],
                                ['name' => 'Mata Uji', 'sortable' => true],
                                ['name' => 'Pertanyaan', 'sortable' => false],
                                ['name' => '', 'className' => 'w-1', 'sortable' => false]
                            ]"
                        >
                            @forelse($paket->komposisi as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="text-muted">{{ $item->soal->mataUji->nama_mata_uji }}</td>
                                <td>{!! strip_tags(substr($item->soal->konten_pertanyaan, 0, 100)) !!}...</td>
                                <td>
                                    <x-tabler.button class="btn-sm btn-outline-danger ajax-delete" 
                                            data-url="{{ route('cbt.paket.remove-soal', [$paket->hashid, $item->hashid]) }}"
                                            data-title="Hapus soal dari paket?"
                                            data-text="Soal tidak terhapus dari Bank Soal, hanya dihapus dari paket ini."
                                            icon="ti ti-trash" />
                                </td>
                            </tr>
                            @empty
                                {{-- Handled by component --}}
                            @endforelse
                        </x-tabler.datatable-client>
                        
                        @if($paket->komposisi->isEmpty())
                            <div class="text-center py-4">Belum ada soal terpilih.</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Bank Soal Tersedia --}}
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Bank Soal Tersedia</h3>
                    </div>
                    <div class="card-body">
                        <form id="form-add-soal" action="{{ route('cbt.paket.add-soal', $paket->hashid) }}" method="POST" class="ajax-form" data-redirect="true">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Pilih Soal (Pilihan Ganda)</label>
                                <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                                    @foreach($soalTersedia as $soal)
                                    <label class="list-group-item">
                                        <input class="form-check-input me-1" type="checkbox" name="soal_ids[]" value="{{ $soal->hashid }}">
                                        <span class="d-block">
                                            <span class="badge bg-blue-lt mb-1">{{ $soal->mataUji->nama_mata_uji }}</span>
                                            <span class="d-block text-muted small">{!! strip_tags(substr($soal->konten_pertanyaan, 0, 150)) !!}</span>
                                        </span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            <x-tabler.button type="submit" class="btn-primary w-100 mt-2" icon="ti ti-plus" text="Tambahkan ke Paket" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@endsection
