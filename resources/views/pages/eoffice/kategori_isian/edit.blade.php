<x-tabler.form-modal
    title="Edit Kategori Isian"
    route="{{ route('eoffice.kategori-isian.update', $kategori->kategoriisian_id) }}"
    method="PUT"
>
    <x-tabler.form-input name="nama_isian" label="Nama Isian" value="{{ $kategori->nama_isian }}" required />
    
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-select name="type" id="type-select-edit" label="Tipe Isian" required>
                <option value="text" {{ $kategori->type == 'text' ? 'selected' : '' }}>Text (Short)</option>
                <option value="textarea" {{ $kategori->type == 'textarea' ? 'selected' : '' }}>Textarea (Long)</option>
                <option value="number" {{ $kategori->type == 'number' ? 'selected' : '' }}>Number</option>
                <option value="date" {{ $kategori->type == 'date' ? 'selected' : '' }}>Date</option>
                <option value="daterange" {{ $kategori->type == 'daterange' ? 'selected' : '' }}>Date Range</option>
                <option value="select" {{ $kategori->type == 'select' ? 'selected' : '' }}>Select (Pilihan)</option>
                <option value="select_api" {{ $kategori->type == 'select_api' ? 'selected' : '' }}>Select via API</option>
                <option value="file" {{ $kategori->type == 'file' ? 'selected' : '' }}>File Upload (DOCX/PDF)</option>
            </x-tabler.form-select>
        </div>
        <div class="col-md-6">
            <x-tabler.form-input name="alias_on_document" label="Alias di Dokumen" value="{{ $kategori->alias_on_document }}" />
        </div>
    </div>

    @php
        $typeValue = is_array($kategori->type_value) ? $kategori->type_value : ($kategori->type_value ? json_decode($kategori->type_value, true) : []);
    @endphp

    <div id="select-options-container-edit" class="mb-3" style="{{ $kategori->type == 'select' ? '' : 'display: none;' }}">
        @if(!empty($typeValue))
            @foreach($typeValue as $val)
            <div class="input-group mb-2">
                <x-tabler.form-input name="type_value[]" value="{{ $val }}" required="true" />
                <x-tabler.button type="button" class="btn-outline-danger" onclick="removeOptionEdit(this)" text="Hapus" />
            </div>
            @endforeach
        @else
            <div class="input-group mb-2">
                <input type="text" name="type_value[]" class="form-control" placeholder="Masukkan opsi">
                <x-tabler.button type="button" class="btn-outline-danger" onclick="removeOptionEdit(this)" text="Hapus" />
            </div>
        @endif
        <div id="additional-options-edit"></div>
        <x-tabler.button type="button" class="btn-outline-primary btn-sm mt-2" onclick="addOptionEdit()" icon="ti ti-plus" text="Tambah Opsi" />
    </div>

    <x-tabler.form-textarea name="keterangan_isian" label="Keterangan" rows="2" value="{{ $kategori->keterangan_isian }}" />
</x-tabler.form-modal>

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
