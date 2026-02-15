
<form class="ajax-form" action="{{ route('sys.permissions.store') }}" method="POST">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Tambah Hak Akses</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            <x-tabler.form-input name="name" label="Permission Name" id="name" required="true" />
            <div class="form-text">Use lowercase letters and underscores only (e.g., manage users, view dashboard)</div>
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="cancel" data-bs-dismiss="modal" />
        <x-tabler.button type="submit" />
    </div>
</form>
