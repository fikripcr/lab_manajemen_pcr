<?php

if (! function_exists('pemutuJenisLabel')) {
    /**
     * Get human-readable label for a document type (jenis).
     * Single source of truth for all display labels.
     */
    function pemutuJenisLabel($jenis)
    {
        return match (strtolower(trim($jenis))) {
            'visi'            => 'Visi',
            'misi'            => 'Misi',
            'rjp'             => 'RPJP',
            'renstra'         => 'Renstra',
            'renop'           => 'Renop',
            'standar'         => 'Standar',
            'formulir'        => 'Formulir',
            'manual_prosedur' => 'Manual Prosedur',
            'kebijakan'       => 'Kebijakan',
            default           => ucfirst($jenis ?? '-'),
        };
    }
}

if (! function_exists('pemutuJenisLabelFull')) {
    /**
     * Get full human-readable label for a document type (jenis).
     */
    function pemutuJenisLabelFull($jenis)
    {
        return match (strtolower(trim($jenis))) {
            'visi'            => 'Visi',
            'misi'            => 'Misi',
            'rjp'             => 'Rencana Pembangunan Jangka Panjang (RPJP)',
            'renstra'         => 'Rencana Strategis (Renstra)',
            'renop'           => 'Rencana Operasional (Renop)',
            'standar'         => 'Standar',
            'formulir'        => 'Formulir',
            'manual_prosedur' => 'Manual Prosedur',
            'kebijakan'       => 'Kebijakan',
            default           => ucfirst($jenis ?? '-'),
        };
    }
}

if (! function_exists('pemutuChildLabel')) {
    /**
     * Get label for child elements based on parent document type.
     */
    function pemutuChildLabel($jenis)
    {
        return match (strtolower(trim($jenis))) {
            'visi', 'misi', 'rjp', 'renstra', 'renop' => 'Poin',
            'standar' => 'Sub Standar',
            default   => 'Turunan'
        };
    }
}

if (! function_exists('pemutuIsDokSubBased')) {
    /**
     * Check if document type uses Sub-Documents (DokSub) for its children.
     */
    function pemutuIsDokSubBased($jenis)
    {
        return in_array(strtolower(trim($jenis)), [
            'standar', 'formulir', 'manual_prosedur', 'renop',
            'visi', 'misi', 'rjp', 'renstra'
        ]);
    }
}

if (! function_exists('pemutuTabByJenis')) {
    /**
     * Get the active tab category for document type.
     */
    function pemutuTabByJenis($jenis)
    {
        $standarTypes = ['standar', 'formulir', 'manual_prosedur'];
        return in_array(strtolower(trim($jenis)), $standarTypes) ? 'standar' : 'kebijakan';
    }
}

if (! function_exists('pemutuFixedJenis')) {
    /**
     * Get the next document type in the hierarchy chain.
     */
    function pemutuFixedJenis($jenis)
    {
        return match (strtolower(trim($jenis))) {
            'visi'    => 'misi',
            'misi'    => 'rjp',
            'rjp'     => 'renstra',
            'renstra' => 'renop',
            default   => null,
        };
    }
}

if (! function_exists('pemutuIndikatorTypeInfo')) {
    /**
     * Get label and color for indicator type.
     */
    function pemutuIndikatorTypeInfo($type)
    {
        $data = [
            'standar'  => ['color' => 'primary', 'label' => 'Standar'],
            'renop'    => ['color' => 'purple',  'label' => 'Renop'],
            'performa' => ['color' => 'success', 'label' => 'Performa'],
        ];

        return $data[strtolower(trim($type))] ?? ['color' => 'secondary', 'label' => ucfirst($type ?? '-')];
    }
}
