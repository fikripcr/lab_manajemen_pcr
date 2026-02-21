@php
    $modelExists = isset($kategori) && $kategori->exists;
@endphp

<x-tabler.form-modal
    title="{{ $modelExists ? 'Edit Kategori Isian' : 'Tambah Kategori Isian' }}"
    route="{{ $modelExists ? route('eoffice.kategori-isian.update', $kategori->encrypted_kategoriisian_id) : route('eoffice.kategori-isian.store') }}"
    method="{{ $modelExists ? 'PUT' : 'POST' }}"
>
    <x-tabler.form-input name="nama_isian" label="Nama Isian" value="{{ $kategori->nama_isian ?? '' }}" placeholder="Contoh: Nama Lengkap, NIP, dsb." required />
    
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-select name="type" id="type-select-ajax" label="Tipe Isian" required>
                <option value="text" {{ ($kategori->type ?? '') == 'text' ? 'selected' : '' }}>Text (Short)</option>
                <option value="textarea" {{ ($kategori->type ?? '') == 'textarea' ? 'selected' : '' }}>Textarea (Long)</option>
                <option value="number" {{ ($kategori->type ?? '') == 'number' ? 'selected' : '' }}>Number</option>
                <option value="date" {{ ($kategori->type ?? '') == 'date' ? 'selected' : '' }}>Date</option>
                <option value="daterange" {{ ($kategori->type ?? '') == 'daterange' ? 'selected' : '' }}>Date Range</option>
                <option value="select" {{ ($kategori->type ?? '') == 'select' ? 'selected' : '' }}>Select (Pilihan)</option>
                <option value="select_api" {{ ($kategori->type ?? '') == 'select_api' ? 'selected' : '' }}>Select via API</option>
                <option value="file" {{ ($kategori->type ?? '') == 'file' ? 'selected' : '' }}>File Upload (DOCX/PDF)</option>
            </x-tabler.form-select>
        </div>
        <div class="col-md-6">
            <x-tabler.form-input name="alias_on_document" label="Alias di Dokumen" value="{{ $kategori->alias_on_document ?? '' }}" placeholder="Contoh: ${nama_lengkap}" help="Gunakan format ${nama_field} untuk template Word." />
        </div>
    </div>

    @php
        $typeValue = [];
        if($modelExists) {
            $typeValue = is_array($kategori->type_value) ? $kategori->type_value : ($kategori->type_value ? json_decode($kategori->type_value, true) : []);
        }
    @endphp

    <div id="select-options-container-ajax" class="mb-3" style="{{ ($kategori->type ?? '') == 'select' ? '' : 'display: none;' }}">
        <div id="additional-options-ajax">
            @if(!empty($typeValue))
                @foreach($typeValue as $val)
                <div class="input-group mb-2">
                    <x-tabler.form-input type="text" name="type_value[]" :value="$val" required class="flex-grow-1" />
                    <button class="btn btn-outline-danger" type="button" onclick="removeOptionAjax(this)">Hapus</button>
                </div>
                @endforeach
            @else
                <div class="input-group mb-2">
                    <x-tabler.form-input type="text" name="type_value[]" placeholder="Masukkan opsi" class="flex-grow-1" />
                    <button class="btn btn-outline-danger" type="button" onclick="removeOptionAjax(this)">Hapus</button>
                </div>
            @endif
        </div>
        <x-tabler.button type="button" class="btn-outline-primary btn-sm mt-2" onclick="addOptionAjax()" icon="ti ti-plus" text="Tambah Opsi" />
    </div>

    <x-tabler.form-textarea name="keterangan_isian" label="Keterangan" rows="2" value="{{ $kategori->keterangan_isian ?? '' }}" placeholder="Keterangan tambahan untuk pengusul" />
</x-tabler.form-modal>

<script>
    $('#type-select-ajax').on('change', function() {
        if ($(this).val() === 'select') {
            $('#select-options-container-ajax').show();
        } else {
            $('#select-options-container-ajax').hide();
        }
    });

    function addOptionAjax() {
        const html = `
            <div class="input-group mb-2">
                <input type="text" name="type_value[]" class="form-control" placeholder="Masukkan opsi">
                <button class="btn btn-outline-danger" type="button" onclick="removeOptionAjax(this)">Hapus</button>
            </div>
        `;
        $('#additional-options-ajax').append(html);
    }

    function removeOptionAjax(btn) {
        $(btn).closest('.input-group').remove();
    }
</script>
