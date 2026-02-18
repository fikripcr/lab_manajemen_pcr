<x-tabler.form-modal
    title="Tambah Tipe Label"
    route="{{ route('pemutu.label-types.store') }}"
    method="POST"
    submitText="Simpan"
>
    <div class="mb-3">
        <x-tabler.form-input name="name" label="Nama" required="true" placeholder="cth: Kategori Indikator" />
    </div>
    <div class="mb-3">
        <x-tabler.form-textarea name="description" label="Deskripsi" rows="2" />
    </div>
    <div class="mb-3">
        <label class="form-label required">Warna</label>
        <div class="d-flex flex-wrap gap-2">
            @foreach(['primary', 'secondary', 'success', 'warning', 'danger', 'info', 'blue', 'azure', 'indigo', 'purple', 'pink', 'red', 'orange', 'yellow', 'lime', 'green', 'teal', 'cyan'] as $color)
            <label class="form-colorinput">
                <input type="radio" name="color" value="{{ $color }}" class="form-colorinput-input" {{ $loop->first ? 'checked' : '' }}>
                <span class="form-colorinput-color bg-{{ $color }}"></span>
            </label>
            @endforeach
        </div>
    </div>
</x-tabler.form-modal>
