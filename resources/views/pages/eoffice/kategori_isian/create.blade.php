<form action="{{ route('eoffice.kategori-isian.store') }}" method="POST">
    @csrf
    <x-tabler.form-input name="nama_isian" label="Nama Isian" placeholder="Contoh: Nama Lengkap, NIP, dsb." required />
    
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-select name="type" id="type-select" label="Tipe Isian" required>
                <option value="text">Text (Short)</option>
                <option value="textarea">Textarea (Long)</option>
                <option value="number">Number</option>
                <option value="date">Date</option>
                <option value="daterange">Date Range</option>
                <option value="select">Select (Pilihan)</option>
                <option value="select_api">Select via API</option>
                <option value="file">File Upload (DOCX/PDF)</option>
            </x-tabler.form-select>
        </div>
        <div class="col-md-6">
            <x-tabler.form-input name="alias_on_document" label="Alias di Dokumen" placeholder="Contoh: ${nama_lengkap}" help="Gunakan format ${nama_field} untuk template Word." />
        </div>
    </div>

    <div id="select-options-container" class="mb-3" style="display: none;">
        <div class="input-group mb-2">
            <x-tabler.form-input name="type_value[]" placeholder="Masukkan opsi" />
            <button class="btn btn-outline-danger" type="button" onclick="removeOption(this)">Hapus</button>
        </div>
        <div id="additional-options"></div>
        <x-tabler.button type="button" class="btn-outline-primary btn-sm mt-2" onclick="addOption()" icon="ti ti-plus">
            Tambah Opsi
        </x-tabler.button>
    </div>

    <x-tabler.form-textarea name="keterangan_isian" label="Keterangan" rows="2" placeholder="Keterangan tambahan untuk pengusul" />

    <div class="text-end">
        <x-tabler.button type="button" class="btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</x-tabler.button>
        <x-tabler.button type="submit" class="btn-primary">Simpan</x-tabler.button>
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
