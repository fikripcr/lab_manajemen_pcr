<?php

if (! function_exists('pmbCurrency')) {
    /**
     * Format numeric value to IDR currency
     *
     * @param float|int $amount
     * @return string
     */
    function pmbCurrency($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (! function_exists('pmbStatusBadge')) {
    /**
     * Get badge HTML for PMB statuses
     *
     * @param string $status
     * @return string
     */
    function pmbStatusBadge($status)
    {
        $status = trim($status);
        $badges = [
            'Menunggu_Pembayaran'       => 'bg-warning-lt',
            'Menunggu_Verifikasi_Pembayaran' => 'bg-info-lt',
            'Pembayaran_Diterima'      => 'bg-success-lt',
            'Pembayaran_Ditolak'       => 'bg-danger-lt',
            'Menunggu_Verifikasi_Berkas' => 'bg-info-lt',
            'Berkas_Valid'             => 'bg-success-lt',
            'Berkas_Kurang'            => 'bg-warning-lt',
            'Siap_Ujian'               => 'bg-primary-lt',
            'Lulus'                    => 'bg-success',
            'Tidak_Lulus'              => 'bg-danger',
        ];
        
        $color = $badges[$status] ?? 'bg-secondary-lt';
        $display = str_replace('_', ' ', $status);
        
        return '<span class="badge ' . $color . '">' . $display . '</span>';
    }
}

if (! function_exists('pmbJalurDisplay')) {
    /**
     * Format Jalur display text
     *
     * @param mixed $jalur
     * @return string
     */
    function pmbJalurDisplay($jalur)
    {
        if (!$jalur) return '-';
        return $jalur->nama . ' (' . $jalur->tahun_akademik . ')';
    }
}
