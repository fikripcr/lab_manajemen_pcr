<form action="{{ route('eoffice.kategori-isian.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label required">Nama Isian</label>
        <input type="text" name="nama_isian" class="form-control" placeholder="Contoh: Nama Lengkap, NIP, dsb." required>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label required">Tipe Isian</label>
            <select name="type" id="type-select" class="form-select" required>
                <option value="text">Text (Short)</option>
                <option value="textarea">Textarea (Long)</option>
                <option value="number">Number</option>
                <option value="date">Date</option>
                <option value="daterange">Date Range</option>
                <option value="select">Select (Pilihan)</option>
                <option value="select_api">Select via API</option>
                <option value="file">File Upload (DOCX/PDF)</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Alias di Dokumen</label>
            <input type="text" name="alias_on_document" class="form-control" placeholder="Contoh: ${nama_lengkap}">
            <small class="text-muted">Gunakan format ${nama_field} untuk template Word.</small>
        </div>
    </div>

    <div id="select-options-container" class="mb-3" style="display: none;">
        <label class="form-label required">Opsi Pilihan (Select)</label>
        <div class="input-group mb-2">
            <input type="text" name="type_value[]" class="form-control" placeholder="Masukkan opsi">
            <button class="btn btn-outline-danger" type="button" onclick="removeOption(this)">Hapus</button>
        </div>
        <div id="additional-options"></div>
        <button class="btn btn-outline-primary btn-sm mt-2" type="button" onclick="addOption()">
            <i class="ti ti-plus"></i> Tambah Opsi
        </button>
    </div>

    <div class="mb-3">
        <label class="form-label">Keterangan</label>
        <textarea name="keterangan_isian" class="form-control" rows="2" placeholder="Keterangan tambahan untuk pengusul"></textarea>
    </div>

    <div class="text-end">
        <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>

<script>
    $('#type-select').on('change', function() {
        if ($(this).val() === 'select') {
            $('#select-options-container').show();
        } else {
            $('#select-options-container').hide();
        }
    });

    function addOption() {
        const html = `
            <div class="input-group mb-2">
                <input type="text" name="type_value[]" class="form-control" placeholder="Masukkan opsi">
                <button class="btn btn-outline-danger" type="button" onclick="removeOption(this)">Hapus</button>
            </div>
        `;
        $('#additional-options').append(html);
    }

    function removeOption(btn) {
        $(btn).closest('.input-group').remove();
    }
</script>
