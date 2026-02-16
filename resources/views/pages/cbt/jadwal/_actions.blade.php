<div class="btn-group">
    <button type="button" class="btn btn-sm btn-warning" onclick="ajaxAction('{{ route('cbt.jadwal.generate-token', $j->encrypted_id) }}', 'table-jadwal')" title="Generate Token Baru">
        <i class="ti ti-rotate"></i>
    </button>
    <button type="button" class="btn btn-sm btn-{{ $j->is_token_aktif ? 'success' : 'secondary' }}" onclick="ajaxAction('{{ route('cbt.jadwal.toggle-token', $j->encrypted_id) }}', 'table-jadwal')" title="{{ $j->is_token_aktif ? 'Nonaktifkan Token' : 'Aktifkan Token' }}">
        <i class="ti ti-{{ $j->is_token_aktif ? 'eye' : 'eye-off' }}"></i>
    </button>
    <button type="button" class="btn btn-sm btn-danger btn-delete" data-url="{{ route('cbt.jadwal.destroy', $j->encrypted_id) }}" data-table="#table-jadwal">
        <i class="ti ti-trash"></i>
    </button>
</div>

<script>
    function ajaxAction(url, tableId) {
        $.post(url, { _token: '{{ csrf_token() }}' }, function(res) {
            if (res.status === 'success') {
                toastr.success(res.message);
                if (window.LaravelDataTables && window.LaravelDataTables[tableId]) {
                    window.LaravelDataTables[tableId].ajax.reload();
                } else {
                    location.reload();
                }
            } else {
                toastr.error(res.message);
            }
        });
    }
</script>
