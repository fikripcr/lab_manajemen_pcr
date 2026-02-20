<div class="btn-group">
    <x-tabler.button href="{{ route('pmb.syarat-jalur.index', ['jalur' => $j->encrypted_jalur_id]) }}" class="btn-sm btn-warning" title="Syarat Dokumen" icon="ti ti-file-certificate" />
    <x-tabler.button type="button" class="btn-sm btn-info ajax-modal-btn"
        data-modal-target="#modalAction" data-modal-title="Edit Jalur"
        data-url="{{ route('pmb.jalur.edit', $j->encrypted_jalur_id) }}" icon="ti ti-edit" />
        
    <x-tabler.button type="button" class="btn-sm btn-danger ajax-delete"
        data-url="{{ route('pmb.jalur.destroy', $j->encrypted_jalur_id) }}"
        data-title="Hapus Jalur?" icon="ti ti-trash" />
</div>
