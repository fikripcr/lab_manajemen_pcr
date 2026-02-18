<x-tabler.form-modal
    title="Tambah Jenis Indisipliner"
    route="{{ route('hr.jenis-indisipliner.store') }}"
    method="POST"
>
    <div class="mb-3">
        <x-tabler.form-input name="jenis_indisipliner" label="Jenis Indisipliner" required="true" placeholder="Contoh: Teguran Lisan, Surat Peringatan, dll" />
    </div>
</x-tabler.form-modal>
