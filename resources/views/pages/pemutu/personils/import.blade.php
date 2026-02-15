<div class="modal-header">
    <h5 class="modal-title">Import Personil</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('pemutu.personils.import') }}" method="POST" enctype="multipart/form-data" class="ajax-form">
    @csrf
    <div class="modal-body">
        <div class="mb-3">
            <x-tabler.form-input type="file" name="file" label="Upload CSV/Excel" required="true" accept=".csv, .xls, .xlsx" help="Format: nama, email, unit_code (or unit_name), jenis, nip." />
        </div>
        <div class="alert alert-info">
            <h4 class="alert-title">Import Instructions</h4>
            <div class="text-muted">
                Ensure your file includes a header row. Columns required: <strong>nama</strong>. Optional: <strong>email, unit_code, jenis, nip</strong>.
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" text="Close" class="btn-link link-secondary" data-bs-dismiss="modal" />
        <x-tabler.button type="submit" text="Import" icon="ti ti-upload" />
    </div>
</form>
