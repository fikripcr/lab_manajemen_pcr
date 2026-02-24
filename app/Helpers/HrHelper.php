<?php

if (! function_exists('hrStatusBadge')) {
    /**
     * Get badge HTML for HR statuses
     *
     * @param string $status
     * @param string $module (perizinan, lembur, approval)
     * @return string
     */
    function hrStatusBadge($status, $module = 'perizinan')
    {
        $status = strtolower(trim($status));
        
        if ($module === 'lembur') {
            $badges = [
                'diajukan' => 'bg-warning',
                'pending'  => 'bg-warning',
                'approved' => 'bg-success',
                'rejected' => 'bg-danger',
            ];
            $color = $badges[$status] ?? 'bg-secondary';
            return '<span class="badge ' . $color . ' text-white">' . ucfirst($status) . '</span>';
        }

        // Default for perizinan/general
        $badges = [
            'draft'    => 'bg-secondary-lt',
            'diajukan' => 'bg-warning',
            'pending'  => 'bg-warning-lt',
            'approved' => 'bg-success-lt',
            'rejected' => 'bg-danger-lt',
        ];
        $color = $badges[$status] ?? 'bg-secondary-lt';
        $textColor = str_contains($color, '-lt') ? '' : 'text-white';
        
        return '<span class="badge ' . $color . ' ' . $textColor . '">' . ucfirst($status) . '</span>';
    }
}

if (! function_exists('hrModelLabel')) {
    /**
     * Convert model class string to a human-readable label
     *
     * @param string $modelClass
     * @return string
     */
    function hrModelLabel($modelClass)
    {
        if (!$modelClass) return '-';
        
        try {
            $shortName = (new \ReflectionClass($modelClass))->getShortName();
            // Optional: convert camelCase to space separated names if needed
            // e.g. RiwayatPendidikan -> Riwayat Pendidikan
            return preg_replace('/(?<!^)[A-Z]/', ' $0', $shortName);
        } catch (\Exception $e) {
            return $modelClass;
        }
    }
}

if (! function_exists('hrDateRange')) {
    /**
     * Format a date range string
     *
     * @param mixed $start
     * @param mixed $end
     * @param string $format
     * @return string
     */
    function hrDateRange($start, $end, $format = 'd/m/Y')
    {
        $startStr = $start instanceof \DateTimeInterface ? $start->format($format) : ($start ?? '-');
        $endStr   = $end instanceof \DateTimeInterface ? $end->format($format) : ($end ?? '-');
        
        return $startStr . ' s/d ' . $endStr;
    }
}

if (! function_exists('hrPegawaiName')) {
    /**
     * Format pegawai name (Inisial - Nama)
     *
     * @param mixed $pegawai
     * @return string
     */
    function hrPegawaiName($pegawai)
    {
        if (!$pegawai) return 'N/A';
        
        $dataDiri = $pegawai->latestDataDiri ?? null;
        if ($dataDiri) {
            return ($dataDiri->inisial ? $dataDiri->inisial . ' - ' : '') . $dataDiri->nama;
        }
        
        return $pegawai->nama ?? 'N/A';
    }
}
