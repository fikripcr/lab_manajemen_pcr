<x-tabler.form-modal
    id_form="{{ $laporan->exists ? 'editLaporanKerusakanForm' : 'createLaporanKerusakanForm' }}"
    title="{{ $laporan->exists ? 'Update Laporan Kerusakan' : 'Lapor Kerusakan' }}"
    route="{{ $laporan->exists ? route('lab.laporan-kerusakan.updateStatus', $laporan->encrypted_laporan_kerusakan_id) : route('lab.laporan-kerusakan.store') }}"
    method="{{ $laporan->exists ? 'PUT' : 'POST' }}"
    enctype="multipart/form-data"
>
    @if(!$laporan->exists)
        <x-tabler.form-select 
            id="select-lab" 
            name="lab_id" 
            label="Lab" 
            :options="$labs->mapWithKeys(fn($lab) => [encryptId($lab->lab_id) => $lab->name])->toArray()" 
            placeholder="Pilih Lab" 
            required 
            data-placeholder="Pilih Lab"
        />

        <div class="mt-3">
            <x-tabler.form-select 
                id="select-inventaris" 
                name="inventaris_id" 
                label="Inventaris / Alat" 
                :options="[]" 
                placeholder="Pilih Lab Terlebih Dahulu" 
                required 
                disabled 
                data-placeholder="Pilih Alat"
                help="Pilih alat yang rusak. Jika fasilitas umum (AC, Pintu), pilih item terkait jika ada." 
            />
        </div>

        <div class="mt-3">
            <x-tabler.form-textarea 
                name="deskripsi_kerusakan" 
                label="Deskripsi Kerusakan" 
                rows="4" 
                placeholder="Jelaskan detail kerusakan..." 
                required 
            >{{ old('deskripsi_kerusakan', $laporan->deskripsi_kerusakan) }}</x-tabler.form-textarea>
        </div>

        <div class="mt-3">
            <x-tabler.form-input 
                type="file" 
                name="bukti_foto" 
                label="Bukti Foto" 
                accept="image/*" 
            />
        </div>
    @else
        <div class="mb-3">
            <label class="form-label">Status Perbaikan</label>
            <x-tabler.form-select 
                name="status" 
                :options="[
                    'open' => 'Open',
                    'in_progress' => 'In Progress',
                    'resolved' => 'Resolved',
                    'closed' => 'Closed'
                ]" 
                selected="{{ $laporan->status }}"
                required
            />
        </div>

        <x-tabler.form-textarea 
            name="catatan_perbaikan" 
            label="Catatan Teknisi / Perbaikan" 
            rows="4" 
            placeholder="Masukkan catatan perbaikan jika ada..."
        >{{ old('catatan_perbaikan', $laporan->catatan_perbaikan) }}</x-tabler.form-textarea>
    @endif

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.loadSelect2();

            document.getElementById('select-lab')?.addEventListener('change', function() {
                const labId = this.value;
                const invSelect = document.getElementById('select-inventaris');

                if (labId) {
                    invSelect.disabled = true;
                    invSelect.innerHTML = '<option>Loading...</option>';

                    axios.get('{{ route("lab.laporan-kerusakan.inventaris") }}', { params: { lab_id: labId } })
                        .then(function(response) {
                            invSelect.innerHTML = '<option value="">Pilih Inventaris</option>';
                            if (response.data.data) {
                                response.data.data.forEach(function(item) {
                                    const opt = new Option(item.text, item.id);
                                    invSelect.add(opt);
                                });
                            }
                            invSelect.disabled = false;
                            // Re-init Select2 if loaded
                            if (window.$) $(invSelect).trigger('change.select2');
                        })
                        .catch(function() {
                            invSelect.innerHTML = '<option value="">Gagal memuat inventaris</option>';
                            invSelect.disabled = false;
                        });
                } else {
                    invSelect.disabled = true;
                    invSelect.innerHTML = '<option value="">Pilih Lab Terlebih Dahulu</option>';
                }
            });
        });
    </script>
    @endpush
</x-tabler.form-modal>
