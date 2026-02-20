<x-tabler.form-modal
    title="Impor Pegawai"
    route="{{ route('pemutu.pegawai.import') }}"
    method="POST"
    submitText="Import"
    submitIcon="ti-upload"
    enctype="multipart/form-data"
>
    <x-tabler.form-input type="file" name="file" label="Upload CSV/Excel" required="true" accept=".csv, .xls, .xlsx" help="Format: nama, email, unit_code (or unit_name), jenis, nip." />
    <div class="alert alert-info mt-3">
        <h4 class="alert-title">Import Instructions</h4>
        <div class="text-muted">
            Ensure your file includes a header row. Columns required: <strong>nama</strong>. Optional: <strong>email, unit_code, jenis, nip</strong>.
        </div>
    </div>
</x-tabler.form-modal>
