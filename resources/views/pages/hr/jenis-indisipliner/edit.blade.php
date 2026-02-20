<x-tabler.form-modal
    title="Edit Jenis Indisipliner"
    route="{{ route('hr.jenis-indisipliner.update', $jenisIndisipliner->encrypted_jenisindisipliner_id) }}"
    method="PUT"
    submitText="Simpan Perubahan"
>
    <div class="mb-3">
        <x-tabler.form-input name="jenis_indisipliner" label="Jenis Indisipliner" value="{{ $jenisIndisipliner->jenis_indisipliner }}" required="true" />
    </div>
</x-tabler.form-modal>
