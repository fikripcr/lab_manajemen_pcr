<x-tabler.form-modal
    title="Edit Info Tambahan"
    route="{{ route('eoffice.jenis-layanan.update-isian-info', $isian->hashid) }}"
    method="POST"
>
    <div class="mb-3">
        <x-tabler.form-textarea name="info" label="Informasi Tambahan / Tooltip" value="{{ $isian->info ?? '' }}" placeholder="Informasi bantuan untuk user saat mengisi field ini" />
    </div>
</x-tabler.form-modal>
