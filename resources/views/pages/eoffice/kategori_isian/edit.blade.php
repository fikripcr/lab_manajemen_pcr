<form action="{{ route('eoffice.kategori-isian.update', $kategori->kategoriisian_id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label required">Nama Isian</label>
        <input type="text" name="nama_isian" class="form-control" value="{{ $kategori->nama_isian }}" required>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label required">Tipe Isian</label>
            <select name="type" id="type-select-edit" class="form-select" required>
                <option value="text" {{ $kategori->type == 'text' ? 'selected' : '' }}>Text (Short)</option>
                <option value="textarea" {{ $kategori->type == 'textarea' ? 'selected' : '' }}>Textarea (Long)</option>
                <option value="number" {{ $kategori->type == 'number' ? 'selected' : '' }}>Number</option>
                <option value="date" {{ $kategori->type == 'date' ? 'selected' : '' }}>Date</option>
                <option value="daterange" {{ $kategori->type == 'daterange' ? 'selected' : '' }}>Date Range</option>
                <option value="select" {{ $kategori->type == 'select' ? 'selected' : '' }}>Select (Pilihan)</option>
                <option value="select_api" {{ $kategori->type == 'select_api' ? 'selected' : '' }}>Select via API</option>
                <option value="file" {{ $kategori->type == 'file' ? 'selected' : '' }}>File Upload (DOCX/PDF)</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Alias di Dokumen</label>
            <input type="text" name="alias_on_document" class="form-control" value="{{ $kategori->alias_on_document }}">
        </div>
    </div>

    @php
        $typeValue = is_array($kategori->type_value) ? $kategori->type_value : ($kategori->type_value ? json_decode($kategori->type_value, true) : []);
    @endphp

    <div id="select-options-container-edit" class="mb-3" style="{{ $kategori->type == 'select' ? '' : 'display: none;' }}">
        <label class="form-label required">Opsi Pilihan (Select)</label>
        @if(!empty($typeValue))
            @foreach($typeValue as $val)
            <div class="input-group mb-2">
                <input type="text" name="type_value[]" class="form-control" value="{{ $val }}">
                <button class="btn btn-outline-danger" type="button" onclick="removeOptionEdit(this)">Hapus</button>
            </div>
            @endforeach
        @else
            <div class="input-group mb-2">
                <input type="text" name="type_value[]" class="form-control" placeholder="Masukkan opsi">
                <button class="btn btn-outline-danger" type="button" onclick="removeOptionEdit(this)">Hapus</button>
            </div>
        @endif
        <div id="additional-options-edit"></div>
        <button class="btn btn-outline-primary btn-sm mt-2" type="button" onclick="addOptionEdit()">
            <i class="ti ti-plus"></i> Tambah Opsi
        </button>
    </div>

    <div class="mb-3">
        <label class="form-label">Keterangan</label>
        <textarea name="keterangan_isian" class="form-control" rows="2">{{ $kategori->keterangan_isian }}</textarea>
    </div>

    <div class="text-end">
        <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </div>
</form>

<script>
    $('#type-select-edit').on('change', function() {
        if ($(this).val() === 'select') {
            $('#select-options-container-edit').show();
        } else {
            $('#select-options-container-edit').hide();
        }
    });

    function addOptionEdit() {
        const html = `
            <div class="input-group mb-2">
                <input type="text" name="type_value[]" class="form-control" placeholder="Masukkan opsi">
                <button class="btn btn-outline-danger" type="button" onclick="removeOptionEdit(this)">Hapus</button>
            </div>
        `;
        $('#additional-options-edit').append(html);
    }

    function removeOptionEdit(btn) {
        $(btn).closest('.input-group').remove();
    }
</script>
