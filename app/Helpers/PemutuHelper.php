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
if (! function_exists('pemutuDtColNo')) {
    /**
     * Render the first column (# / No) for Indikator DataTables.
     * Contains No, Level/Type, Kelompok, Quality, Risk, ID, and Search Icon.
     */
    function pemutuDtColNo($row)
    {
        $origin = $row->origin_from ?? '';
        $type   = $row->type ? ucfirst($row->type) : 'Standar';
        $kel    = $row->kelompok_indikator ?? '-';
        $risk   = strtoupper($row->level_risk ?? 'NO RISK');
        $jd     = $row->jenis_data ?? '-';
        $base   = $row->parent_no_indikator ?? ($row->parent?->no_indikator ?? null);
        $id     = $row->indikator_id ?? null;

        $riskColor = match ($risk) {
            'HIGH RISK'   => 'danger',
            'MEDIUM RISK' => 'warning',
            'LOW RISK'    => 'info',
            default       => 'secondary',
        };

        $html  = '<div class="text-center small d-flex flex-column gap-1 align-items-center" style="min-width: 70px;">';
        $html .= '<div class="text-muted fw-bold" style="font-size: 10px;">' . e(ucfirst($origin)) . '</div>';
        $html .= '<div class="badge bg-secondary-lt text-uppercase" style="font-size: 9px;">' . e($type) . '</div>';

        if ($kel !== '-') {
            $html .= '<div class="text-muted mt-1" style="font-size: 9px;">' . e($kel) . '</div>';
        }

        $html .= '<span class="badge bg-' . $riskColor . ' text-white px-1 mt-1" style="font-size: 9px;">' . e($risk) . '</span>';

        if ($jd !== '-') {
            $html .= '<span class="status status-blue status-lite py-0 px-1 fw-bold mt-1" style="font-size: 10px;">' . e($jd) . '</span>';
        }

        if ($base) {
            $html .= '<div class="text-muted mt-1" style="font-size: 9px; line-height: 1;">Base: [' . e($base) . ']</div>';
        }

        // No more search icon here
        $html .= '</div>';

        return $html;
    }
}

if (! function_exists('pemutuDtColIndikator')) {
    /**
     * Render the second column (Indikator Content) for Indikator DataTables.
     * Contains Standar Header, Indikator Text, Labels, and Responsible Unit/Person.
     */
    function pemutuDtColIndikator($row)
    {
        // 1. Standar Header
        $standar = '-';
        if (isset($row->doksub_details) && $row->doksub_details !== '-') {
            $details = explode(' ;; ', $row->doksub_details);
            $first   = explode('|', $details[0]);
            $standar = ($first[1] ?? '') . ' ' . $first[0];
        } elseif (isset($row->dokSubs) && $row->dokSubs->isNotEmpty()) {
            $ds  = $row->dokSubs->first();
            $doc = $ds->dokumen ?? null;
            // Prefer root standard title (parent document)
            if ($doc && $doc->parent) {
                $standar = ($doc->parent->kode ?? '') . ' ' . $doc->parent->judul;
            } else {
                $standar = ($ds->kode ?? '') . ' ' . $ds->judul;
            }
            if ($doc && $doc->periode) {
                $standar .= ' (' . $doc->periode . ')';
            }
        }

        // 2. Body Text
        $text = $row->indikator ?? '-';

        // 3. Labels
        $labelHtml = '';
        if (isset($row->label_details) && $row->label_details !== '-') {
            $labels = explode(', ', $row->label_details);
            foreach ($labels as $lbl) {
                if (strpos($lbl, '|') !== false) {
                    [$name, $color]  = explode('|', $lbl);
                    $labelHtml      .= '<span class="status status-' . e($color) . ' status-lite small me-1">' . e($name) . '</span>';
                }
            }
        } elseif (isset($row->labels) && ! is_string($row->labels) && $row->labels->isNotEmpty()) {
            $labelHtml = pemutuLabelBadges($row->labels);
        }

        // 4. Responsible
        $resp = $row->unit_name ?? ($row->unit_code ?? null);
        if (! $resp && isset($row->orgUnits) && ! is_string($row->orgUnits) && $row->orgUnits->isNotEmpty()) {
            $resp = $row->orgUnits->first()->name ?? $row->orgUnits->first()->code;
        }
        if (! $resp && isset($row->pegawai_name)) {
            $resp = $row->pegawai_name;
        }

        $html = '<div class="d-flex flex-column gap-1">';
        $html .= '<div class="text-muted small fw-bold opacity-75 mb-1"><i class="ti ti-bell-ringing me-1"></i>' . e($standar) . '</div>';

        // Inline No + Text with Scroll
        $no    = $row->no_indikator ?? '-';
        $id    = $row->indikator_id ?? null;
        $html .= '<div style="max-height: 20vh; overflow-y: auto; scrollbar-width: thin;" class="pe-2 mb-1">';
        if ($id) {
            $url   = route('pemutu.indikator.show', encryptId($id));
            $html .= '<a href="' . $url . '" class="text-primary fw-bold me-1" title="Lihat Detail Indikator">[' . e($no) . ']</a>';
        } else {
            $html .= '<strong class="me-1">[' . e($no) . ']</strong>';
        }
        $html .= '<span class="fw-medium lh-base">' . e($text) . '</span>';
        $html .= '</div>';

        if ($labelHtml) {
            $html .= '<div class="mb-1">' . $labelHtml . '</div>';
        }

        if ($resp) {
            $html .= '<div class="small text-muted d-flex align-items-center gap-1 opacity-75">';
            $html .= '<i class="ti ti-user fs-3"></i>';
            $html .= '<span class="fw-bold">' . e($resp) . '</span>';
            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;
    }
}

if (! function_exists('pemutuDtColTarget')) {
    /**
     * Render the third column (Target) for Indikator DataTables.
     */
    function pemutuDtColTarget($row)
    {
        $target = $row->target_indikator ?? ($row->target ?? '-');
        if (isset($row->orgUnits) && ! is_string($row->orgUnits) && $row->orgUnits->isNotEmpty()) {
            $pivotTarget = $row->orgUnits->first()->pivot->target ?? null;
            if ($pivotTarget) {
                $target = $pivotTarget;
            }
        }

        $html = '<div class="d-flex flex-column">';
        $html .= '<div class="fw-bold">' . e($target) . '</div>';
        $html .= '</div>';

        return $html;
    }
}
