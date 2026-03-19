{{--
    Shared action buttons for Dokumen detail.
    Requires: $dokumen, $childLabel, $isDokSubBased
--}}
{{-- @if($dokumen->jenis === 'renop') --}}
    <x-tabler.button type="button" class="btn-primary d-none d-sm-inline-block"
        href="{{ route('pemutu.dokumen.show-renop-with-indicators', $dokumen->encrypted_dok_id) }}"
        icon="ti ti-chart-bar" text="Akumulasi Indikator" />
{{-- @endif --}}

<x-tabler.button-group>
    <x-tabler.button href="#" class="btn-white ajax-modal-btn"
        data-url="{{ route('pemutu.dokumen.edit', $dokumen->encrypted_dok_id) }}"
        data-modal-title="Edit Dokumen" icon="ti ti-pencil" text="Edit" />

    @if($isDokSubBased)
        <x-tabler.button type="create" href="#" class="btn-outline-primary ajax-modal-btn"
            data-url="{{ route('pemutu.dok-subs.create', ['dok_id' => $dokumen->encrypted_dok_id]) }}"
            data-modal-title="Tambah {{ $childLabel }}" text="{{ $childLabel }}" />
    @else
        <x-tabler.button type="create" href="#" class="btn-outline-primary ajax-modal-btn"
            data-url="{{ route('pemutu.dokumen.create', ['parent_id' => $dokumen->encrypted_dok_id]) }}"
            data-modal-title="Tambah {{ $childLabel }}" text="{{ $childLabel }}" />
    @endif

    <x-tabler.button type="delete" class="btn-outline-danger ajax-delete"
        data-url="{{ route('pemutu.dokumen.destroy', $dokumen->encrypted_dok_id) }}"
        data-title="Hapus Dokumen?"
        data-text="Dokumen ini beserta sub-dokumennya akan dihapus permanen."
        icon="ti ti-trash" text="Hapus" />
</x-tabler.button-group>
