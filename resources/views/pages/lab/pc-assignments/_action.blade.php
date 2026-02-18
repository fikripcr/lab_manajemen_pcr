@php
    $encryptedId = encryptId($row->pc_assignments_id);
@endphp
<x-tabler.button type="button" class="btn-sm btn-icon btn-outline-danger" onclick="deleteAssignment('{{ route('lab.jadwal.assignments.destroy', [$jadwalId, $encryptedId]) }}')" icon="bx bx-trash" />
