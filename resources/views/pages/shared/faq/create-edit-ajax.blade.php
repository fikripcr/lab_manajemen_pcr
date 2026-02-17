<div class="modal-header">
    <h5 class="modal-title">{{ $faq->exists ? 'Edit FAQ' : 'Tambah FAQ' }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="ajax-form" action="{{ $faq->exists ? route('shared.faq.update', $faq->hashid) : route('shared.faq.store') }}" method="POST">
    @csrf
    @if($faq->exists) @method('PUT') @endif
    <div class="modal-body">
        <x-tabler.flash-message />
        
        <x-tabler.form-input 
            name="question" 
            label="Pertanyaan" 
            value="{{ $faq->question }}"
            required="true"
        />

        <x-tabler.form-textarea 
            name="answer" 
            label="Jawaban" 
            type="editor"
            height="300"
            required="true"
        >{{ $faq->answer }}</x-tabler.form-textarea>

        <div class="row">
            <div class="col-md-6">
                <x-tabler.form-input 
                    name="category" 
                    label="Kategori (Opsional)" 
                    value="{{ $faq->category }}"
                />
            </div>
            <div class="col-md-6">
                <x-tabler.form-input 
                    name="seq" 
                    label="Urutan" 
                    type="number"
                    value="{{ $faq->seq ?? 0 }}"
                />
            </div>
        </div>

        <div class="mt-3">
            <label class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_active" {{ $faq->exists ? ($faq->is_active ? 'checked' : '') : 'checked' }}>
                <span class="form-check-label">Aktifkan FAQ</span>
            </label>
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" text="Batal" class="btn-link link-secondary" data-bs-dismiss="modal" />
        <x-tabler.button type="submit" text="Simpan" />
    </div>
</form>
