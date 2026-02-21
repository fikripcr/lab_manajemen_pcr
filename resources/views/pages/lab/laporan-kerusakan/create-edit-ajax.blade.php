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
        $(document).ready(function() {
            window.loadSelect2();
            
            $('#select-lab').on('change', function() {
                var labId = $(this).val();
                var invSelect = $('#select-inventaris');
                
                if (labId) {
                    invSelect.prop('disabled', true).html('<option>Loading...</option>');
                    
                    $.ajax({
                        url: '{{ route("lab.laporan-kerusakan.inventaris") }}',
                        data: { lab_id: labId },
                        success: function(response) {
                            invSelect.empty().append('<option value="">Pilih Inventaris</option>');
                            if(response.data) {
                                response.data.forEach(function(item) {
                                    invSelect.append(new Option(item.text, item.id));
                                });
                            }
                            invSelect.prop('disabled', false);
                        }
                    });
                } else {
                    invSelect.prop('disabled', true).html('<option value="">Pilih Lab Terlebih Dahulu</option>');
                }
            });
        });
    </script>
    @endpush
</x-tabler.form-modal>
