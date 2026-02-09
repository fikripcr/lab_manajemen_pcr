<div class="modal-header">
    <h5 class="modal-title">Create Personil</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('pemutu.personils.store') }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
        <div class="mb-3">
            <label for="nama" class="form-label required">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama" name="nama" required placeholder="Nama Personil">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="example@pcr.ac.id">
            <small class="form-hint">Used to link with User account automatically.</small>
        </div>
        <div class="mb-3">
            <label for="org_unit_id" class="form-label">Unit Organisasi</label>
            <select class="form-select select2-offline" id="org_unit_id" name="org_unit_id" data-dropdown-parent="#modalAction">
                <option value="">Select Unit</option>
                @foreach($units as $u)
                    <option value="{{ $u->orgunit_id }}">{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="jenis" class="form-label">Jenis</label>
            <select class="form-select" id="jenis" name="jenis">
                <option value="Dosen">Dosen</option>
                <option value="Staff">Staff</option>
                <option value="Mahasiswa">Mahasiswa</option>
                <option value="Lainnya">Lainnya</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="nip" class="form-label">NIP / ID External</label>
            <input type="text" class="form-control" id="external_id" name="external_id">
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" text="Close" class="btn-link link-secondary" data-bs-dismiss="modal" />
        <x-tabler.button type="submit" text="Simpan" />
    </div>
</form>
