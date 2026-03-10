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
            'visi', 'misi', 'rjp', 'renstra', 'renop' => 'Poin', 'standar' => 'Poin',
            default => 'Turunan'
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
            'visi', 'misi', 'rjp', 'renstra',
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
            'standar'  => ['color' => 'primary', 'label' => 'Indikator Standar', 'short-label' => 'ISTD'],
            'renop'    => ['color' => 'purple', 'label' => 'Indikator Renop', 'short-label' => 'IRNP'],
            'performa' => ['color' => 'success', 'label' => 'Indikator Performa', 'short-label' => 'IPRF'],
        ];

        return $data[strtolower(trim($type))] ?? ['color' => 'secondary', 'label' => ucfirst($type ?? '-'), 'short-label' => 'IND'];
    }
}

if (! function_exists('pemutuMappableJenis')) {
    /**
     * Get the valid target document type for point-to-point mapping.
     * Returns which document type's points a given type can map to.
     * E.g. misi points can map to visi points, rpjp to misi, etc.
     */
    function pemutuMappableJenis($jenis)
    {
        return match (strtolower(trim($jenis))) {
            'misi'    => 'visi',
            'rjp'     => 'misi',
            'renstra' => 'rjp',
            'renop'   => 'renstra',
            default   => null, // visi: top level, no mapping target
        };
    }
}

if (! function_exists('pemutuKebijakanJenisList')) {
    /**
     * Get the ordered list of 5 kebijakan document types.
     */
    function pemutuKebijakanJenisList(): array
    {
        return ['visi', 'misi', 'rjp', 'renstra', 'renop'];
    }
}

if (! function_exists('pemutuLabelBadge')) {
    /**
     * Render a single label badge HTML with correct color from its LabelType.
     *
     * @param  \App\Models\Pemutu\Label  $label
     * @param  string  $style  'lt' (light) or 'solid'
     * @return string  HTML badge string
     */
    function pemutuLabelBadge($label, string $style = 'lt'): string
    {
        $color = $label->type->color ?? 'secondary';

        if ($style === 'solid') {
            return '<span class="badge text-bg-' . e($color) . '">' . e($label->name) . '</span>';
        }

        return '<span class="badge bg-' . e($color) . '-lt text-' . e($color) . '">' . e($label->name) . '</span>';
    }
}

if (! function_exists('pemutuLabelBadges')) {
    /**
     * Render multiple label badges from a collection, joined with spaces.
     *
     * @param  \Illuminate\Support\Collection  $labels
     * @param  string  $style  'lt' (light) or 'solid'
     * @return string  HTML string of all badges
     */
    function pemutuLabelBadges($labels, string $style = 'lt'): string
    {
        return $labels->map(fn($l) => pemutuLabelBadge($l, $style))->implode(' ');
    }
}
