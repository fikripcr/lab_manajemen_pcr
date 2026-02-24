<?php

if (! function_exists('labConditionBadge')) {
    /**
     * Get badge HTML for Lab item conditions
     *
     * @param string $condition
     * @return string
     */
    function labConditionBadge($condition)
    {
        $condition = trim($condition);
        $badges = [
            'Baik'                  => 'bg-success-lt',
            'Rusak Ringan'          => 'bg-warning-lt',
            'Rusak Berat'           => 'bg-danger-lt',
            'Tidak Dapat Digunakan' => 'bg-dark-lt',
        ];
        
        $color = $badges[$condition] ?? 'bg-secondary-lt';
        
        return '<span class="badge ' . $color . '">' . $condition . '</span>';
    }
}

if (! function_exists('labStatusBadge')) {
    /**
     * Get badge HTML for Lab request statuses
     *
     * @param string $status
     * @return string
     */
    function labStatusBadge($status)
    {
        $status = strtolower(trim($status));
        $badges = [
            'diajukan' => 'bg-warning-lt',
            'disetujui' => 'bg-success-lt',
            'ditolak'   => 'bg-danger-lt',
            'selesai'   => 'bg-primary-lt',
        ];
        
        $color = $badges[$status] ?? 'bg-secondary-lt';
        
        return '<span class="badge ' . $color . '">' . ucfirst($status) . '</span>';
    }
}
