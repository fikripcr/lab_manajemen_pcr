<x-tabler.form-modal
    title="Verifikasi Pembayaran: {{ $pembayaran->pendaftaran->no_pendaftaran }}"
    route="{{ route('pmb.verification.verify-payment', $pembayaran->encrypted_pembayaran_id) }}"
    method="POST"
    submitText="Simpan Hasil Verifikasi"
    data-redirect="true"
>
    <div class="row">
        <div class="col-md-5">
            <h3 class="card-title">Bukti Transfer</h3>
            <a href="{{ asset('storage/' . $pembayaran->bukti_bayar) }}" target="_blank">
                <img src="{{ asset('storage/' . $pembayaran->bukti_bayar) }}" class="img-fluid rounded border shadow-sm" alt="Bukti Transfer">
            </a>
        </div>
        <div class="col-md-7">
            <h3 class="card-title">Detail Pembayaran</h3>
            <table class="table table-sm table-borderless">
                <tr><td>Nama Calon</td><td>: {{ $pembayaran->pendaftaran->user->name }}</td></tr>
                <tr><td>Bank Asal</td><td>: {{ $pembayaran->bank_asal }}</td></tr>
                <tr><td>Nama Pengirim</td><td>: {{ $pembayaran->nama_pengirim }}</td></tr>
                <tr><td>Tanggal Bayar</td><td>: {{ formatTanggalIndo($pembayaran->tanggal_bayar) }}</td></tr>
                <tr><td>Nominal</td><td>: <strong>Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</strong></td></tr>
            </table>

            <div class="mt-3">
                <div class="mb-3">
                    <label class="form-label">Hasil Verifikasi</label>
                    <div class="form-selectgroup">
                        <label class="form-selectgroup-item">
                            <input type="radio" name="status" value="Verified" class="form-selectgroup-input" checked>
                            <span class="form-selectgroup-label text-success"><i class="ti ti-check pe-1"></i> Setujui</span>
                        </label>
                        <label class="form-selectgroup-item">
                            <input type="radio" name="status" value="Rejected" class="form-selectgroup-input">
                            <span class="form-selectgroup-label text-danger"><i class="ti ti-x pe-1"></i> Tolak</span>
                        </label>
                    </div>
                </div>
                <x-tabler.form-textarea name="keterangan" label="Keterangan / Alasan (Opsional)" placeholder="Masukkan alasan jika ditolak..." />
            </div>
        </div>
    </div>
</x-tabler.form-modal>
