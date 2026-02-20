<x-tabler.button-group class="flex-nowrap">
    <x-tabler.button href="{{ route('pemutu.dok-subs.edit', $row) }}" class="btn-sm btn-icon btn-outline-primary" icon="ti ti-pencil" title="Edit Content" iconOnly="true" />
    <x-tabler.button type="button" class="btn-sm btn-icon btn-danger ajax-delete" data-url="{{ route('pemutu.dok-subs.destroy', $row) }}" data-title="Hapus Sub-Dokumen?" data-text="Data ini akan dihapus permanen." icon="ti ti-trash" />
</x-tabler.button-group>
