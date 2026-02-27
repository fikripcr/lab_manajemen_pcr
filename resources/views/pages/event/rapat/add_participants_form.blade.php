<x-tabler.form-modal
    title="Tambah Peserta Rapat"
    route="{{ route('Kegiatan.rapat.participants.store', $rapat->hashid) }}"
    method="POST"
    size="modal-lg"
>
    <ul class="nav nav-tabs mb-3" id="tab-tambah-peserta" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#tab-internal">
                <i class="ti ti-users me-1"></i> Pegawai Internal
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#tab-luar">
                <i class="ti ti-user-plus me-1"></i> Peserta Luar
            </a>
        </li>
    </ul>

    <div class="tab-content">
        {{-- Tab Internal --}}
        <div class="tab-pane active show" id="tab-internal">
            <div class="mb-3">
                <label class="form-label">Pilih Peserta <span class="text-muted small">(bisa pilih banyak)</span></label>
                <x-tabler.form-select name="user_ids[]" class="select2-participants" multiple="true" data-placeholder="Cari pegawai...">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} — {{ $user->email }}</option>
                    @endforeach
                </x-tabler.form-select>
            </div>
            <x-tabler.form-input name="jabatan_internal" label="Jabatan / Peran" placeholder="Contoh: Peserta, Narasumber" />
        </div>

        {{-- Tab Luar --}}
        <div class="tab-pane" id="tab-luar">
            <div id="peserta-luar-list">
                <div class="row g-2 mb-2 peserta-luar-row">
                    <div class="col">
                        <input type="text" name="peserta_luar[0][nama]" class="form-control" placeholder="Nama lengkap">
                    </div>
                    <div class="col">
                        <input type="email" name="peserta_luar[0][email]" class="form-control" placeholder="Email">
                    </div>
                    <div class="col">
                        <input type="text" name="peserta_luar[0][jabatan]" class="form-control" placeholder="Jabatan/Peran">
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary mt-1" id="btn-add-luar">
                <i class="ti ti-plus me-1"></i> Tambah Baris
            </button>
        </div>
    </div>

    <script>
        // Logic for adding rows in the modal context
        document.getElementById('btn-add-luar').addEventListener('click', function () {
            const list = document.getElementById('peserta-luar-list');
            const idx = list.querySelectorAll('.peserta-luar-row').length;
            const row = document.createElement('div');
            row.className = 'row g-2 mb-2 peserta-luar-row';
            row.innerHTML = `
                <div class="col"><input type="text" name="peserta_luar[${idx}][nama]" class="form-control" placeholder="Nama lengkap"></div>
                <div class="col"><input type="email" name="peserta_luar[${idx}][email]" class="form-control" placeholder="Email"></div>
                <div class="col"><input type="text" name="peserta_luar[${idx}][jabatan]" class="form-control" placeholder="Jabatan/Peran"></div>
                <div class="col-auto d-flex align-items-center">
                    <button type="button" class="btn btn-sm btn-ghost-danger btn-remove-luar" title="Hapus baris"><i class="ti ti-x"></i></button>
                </div>`;
            list.appendChild(row);
            row.querySelector('.btn-remove-luar').addEventListener('click', () => row.remove());
        });
    </script>
</x-tabler.form-modal>
