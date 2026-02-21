@php
    $encryptedId = encryptId($row->pc_assignments_id);
@endphp
<x-tabler.button type="button" class="btn-sm btn-icon btn-outline-danger ajax-delete" data-url="{{ route('lab.jadwal.assignments.destroy', [$jadwalId, $encryptedId]) }}" data-title="Hapus Assignment PC" icon="bx bx-trash" />
