<x-tabler.form-modal
    title="Edit Rule Validasi"
    route="{{ route('eoffice.jenis-layanan.update-isian-rule', $isian->hashid) }}"
    method="POST"
>
    <div class="mb-3">
        <x-tabler.form-input name="rule" label="Validation Rules (Laravel Standard)" value="{{ $isian->rule ?? '' }}" placeholder="Misal: required|email|max:255" />
        <small class="text-muted">Gunakan pemisah pipa (|) untuk multiple rules.</small>
    </div>
</x-tabler.form-modal>
