<div class="modal-header">
    <h5 class="modal-title">Tambah Tipe Label</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('pemutu.label-types.store') }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
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
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" text="Batal" class="btn-link link-secondary" data-bs-dismiss="modal" />
        <x-tabler.button type="submit" text="Simpan" />
    </div>
</form>
