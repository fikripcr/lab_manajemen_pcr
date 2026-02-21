@extends(request()->ajax() || request()->has('ajax') ? 'layouts.tabler.empty' : 'layouts.tabler.app')

@section('header')
    @if(!request()->ajax() && !request()->has('ajax'))
        <x-tabler.page-header title="{{ $dokumen->judul }}" pretitle="Dokumen SPMI">
            <x-slot:actions>
                @if($dokumen->jenis === 'renop')
                    <x-tabler.button type="button" class="btn-primary d-none d-sm-inline-block" href="{{ route('pemutu.dokumens.show-renop-with-indicators', $dokumen->encrypted_dok_id) }}" icon="ti ti-chart-bar" text="Akumulasi Indikator" />
                @endif

                <x-tabler.button-group>
                    <x-tabler.button href="#" class="btn-white ajax-modal-btn" data-url="{{ route('pemutu.dokumens.edit', $dokumen->encrypted_dok_id) }}" data-modal-title="Edit Dokumen" icon="ti ti-pencil" text="Edit" />
                    @php
                        $childLabel = 'Sub Dokumen';
                        $parentJenis = $dokumen->jenis ? strtolower(trim($dokumen->jenis)) : '';
                        if($parentJenis) {
                            $childLabel = match($parentJenis) {
                                'visi' => 'Poin Visi',
                                'misi' => 'Poin Misi',
                                'rjp' => 'Poin RJP',
                                'renstra' => 'Poin Renstra',
                                'renop' => 'Kegiatan / Poin',
                                default => 'Sub Dokumen'
                            };
                        }
                        $isDokSubBased = in_array($parentJenis, ['standar', 'formulir', 'manual_prosedur', 'renop', 'visi', 'misi', 'rjp', 'renstra']);
                    @endphp
                    @if($isDokSubBased)
                        <x-tabler.button href="#" class="btn-outline-primary ajax-modal-btn" data-url="{{ route('pemutu.dok-subs.create', ['dok_id' => $dokumen->encrypted_dok_id]) }}" data-modal-title="Tambah {{ $childLabel }}" icon="ti ti-plus" text="{{ $childLabel }}" />
                    @else
                        <x-tabler.button href="#" class="btn-outline-primary ajax-modal-btn" data-url="{{ route('pemutu.dokumens.create', ['parent_id' => $dokumen->encrypted_dok_id]) }}" data-modal-title="Tambah {{ $childLabel }}" icon="ti ti-plus" text="{{ $childLabel }}" />
                    @endif
                    <x-tabler.button href="#" class="btn-outline-danger ajax-delete" data-url="{{ route('pemutu.dokumens.destroy', $dokumen->encrypted_dok_id) }}" data-title="Hapus Dokumen?" data-text="Dokumen ini beserta sub-dokumennya akan dihapus permanen." icon="ti ti-trash" text="Hapus" />
                </x-tabler.button-group>
            </x-slot:actions>
        </x-tabler.page-header>
    @endif
@endsection

@section('content')
    @if(request()->ajax() || request()->has('ajax'))
        {{-- AJAX Content --}}
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="card-title mb-0">{{ $dokumen->judul }}</h3>
                <x-tabler.button-group>
                    @if($dokumen->jenis === 'renop')
                        <x-tabler.button type="button" class="btn-primary d-none d-sm-inline-block" href="{{ route('pemutu.dokumens.show-renop-with-indicators', $dokumen->encrypted_dok_id) }}" icon="ti ti-chart-bar" text="Akumulasi" />
                    @endif
                    <x-tabler.button href="#" class="btn-white ajax-modal-btn" data-url="{{ route('pemutu.dokumens.edit', $dokumen->encrypted_dok_id) }}" data-modal-title="Edit Dokumen" icon="ti ti-pencil" text="Edit" />
                    
                    @php
                        $childLabel = 'Sub Dokumen';
                        $parentJenis = $dokumen->jenis ? strtolower(trim($dokumen->jenis)) : '';
                        if($parentJenis) {
                            $childLabel = match($parentJenis) {
                                'visi' => 'Poin Visi',
                                'misi' => 'Poin Misi',
                                'rjp' => 'Poin RJP',
                                'renstra' => 'Poin Renstra',
                                'renop' => 'Kegiatan / Poin',
                                default => 'Sub Dokumen'
                            };
                        }
                        $isDokSubBased = in_array($parentJenis, ['standar', 'formulir', 'manual_prosedur', 'renop', 'visi', 'misi', 'rjp', 'renstra']);
                    @endphp

                    @if($isDokSubBased)
                        <x-tabler.button href="#" class="btn-outline-primary ajax-modal-btn" data-url="{{ route('pemutu.dok-subs.create', ['dok_id' => $dokumen->encrypted_dok_id]) }}" data-modal-title="Tambah {{ $childLabel }}" icon="ti ti-plus" text="{{ $childLabel }}" />
                    @else
                        <x-tabler.button href="#" class="btn-outline-primary ajax-modal-btn" data-url="{{ route('pemutu.dokumens.create', ['parent_id' => $dokumen->encrypted_dok_id]) }}" data-modal-title="Tambah {{ $childLabel }}" icon="ti ti-plus" text="{{ $childLabel }}" />
                    @endif
                    <x-tabler.button href="#" class="btn-outline-danger ajax-delete" data-url="{{ route('pemutu.dokumens.destroy', $dokumen->encrypted_dok_id) }}" data-title="Hapus Dokumen?" data-text="Dokumen ini beserta sub-dokumennya akan dihapus permanen." icon="ti ti-trash" text="Hapus" />
                </x-tabler.button-group>
            </div>

            <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
                @if($dokumen->jenis)
                    <span class="badge badge-outline text-blue bg-blue-lt">{{ strtoupper($dokumen->jenis) }}</span>
                @endif
                @if($dokumen->kode)
                    <span class="badge badge-outline text-muted" title="Kode Dokumen">{{ $dokumen->kode }}</span>
                @endif
                @if($dokumen->periode)
                    <span class="badge badge-outline text-muted" title="Periode">{{ $dokumen->periode }}</span>
                @endif
            </div>

            @include('pages.pemutu.dokumens._detail_content')
        </div>
    @else
        {{-- Full Page Content --}}
        <ol class="breadcrumb" aria-label="breadcrumbs">
            <li class="breadcrumb-item"><a href="{{ route('pemutu.dokumens.index') }}">Dokumen SPMI</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>

        <div class="card">
            <div class="card-body">
                    @include('pages.pemutu.dokumens._detail_content')
            </div>
        </div>
    @endif
@endsection
