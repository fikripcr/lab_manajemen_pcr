<x-tabler.button type="button" class="btn-sm btn-primary ajax-modal-btn" 
    data-modal-target="#modalAction"
    data-modal-title="Verifikasi Pembayaran" 
    data-url="{{ route('pmb.verification.payment-form', $row->encrypted_pendaftaran_id) }}" 
    text="Detail" />
