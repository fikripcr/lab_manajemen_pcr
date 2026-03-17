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
            'visi', 'misi', 'standar' => 'Poin',
            'rjp', 'renstra', 'renop', 'kebijakan' => 'Indikator',
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
            'standar', 'formulir', 'manual_prosedur',
            'visi', 'misi', 'rjp', 'renstra', 'kebijakan'
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
            'kebijakan' => 'standar',
            'standar' => 'manual_prosedur',
            'manual_prosedur' => 'formulir',
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
    function pemutuMappableJenis($jenis): array
    {
        return match (strtolower(trim($jenis))) {
            'misi'     => ['visi'],
            'rjp'      => ['misi'],
            'renstra'  => ['rjp'],
            'renop'    => ['renstra'],
            'standar'  => ['kebijakan'],
            'formulir' => ['standar', 'manual_prosedur'],
            default    => [], // visi: top level, no mapping target
        };
    }
}

if (! function_exists('pemutuKebijakanJenisList')) {
    /**
     * Get the ordered list of 5 kebijakan document types.
     */
    function pemutuKebijakanJenisList(): array
    {
        return ['kebijakan', 'standar', 'manual_prosedur', 'formulir', 'visi', 'misi', 'rjp', 'renstra', 'renop'];
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
        $color = $label->color ?? 'secondary';

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

        // Matrix Data Extraction
        $important = $row->prev_important ?? null;
        $urgent    = $row->prev_urgent ?? null;

        if (! $important && ! $urgent && isset($row->orgUnits) && ! is_string($row->orgUnits) && $row->orgUnits->isNotEmpty()) {
            $pivot     = $row->orgUnits->first()->pivot;
            $important = $pivot->pengend_important_matrix ?? null;
            $urgent    = $pivot->pengend_urgent_matrix ?? null;
        }

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

        // Show Matrix if available, otherwise show Risk
        if ($important || $urgent) {
            if ($important === 'important') {
                $html .= '<span class="badge bg-red-lt text-red px-1 mt-1" style="font-size: 9px;">IMPORTANT</span>';
            }
            if ($urgent === 'urgent') {
                $html .= '<span class="badge bg-orange-lt text-orange px-1 mt-0" style="font-size: 9px;">URGENT</span>';
            }
            if ($important === 'not_important' && $urgent === 'not_urgent') {
                $html .= '<span class="badge bg-secondary-lt text-secondary px-1 mt-1" style="font-size: 9px;">LOW PRIO</span>';
            }
        } else {
            $html .= '<span class="badge bg-' . $riskColor . ' text-white px-1 mt-1" style="font-size: 9px;">' . e($risk) . '</span>';
        }

        if ($jd !== '-') {
            $html .= '<span class="status status-blue status-lite py-0 px-1 fw-bold mt-1" style="font-size: 10px;">' . e($jd) . '</span>';
        }

        if ($base) {
            $html .= '<div class="text-muted mt-1" style="font-size: 9px; line-height: 1;">Base: [' . e($base) . ']</div>';
        }

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
        } elseif (isset($row->dokumen_judul)) {
            $standar = $row->dokumen_judul;
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

if (! function_exists('pemutuDtColAnalisisEd')) {
    /**
     * Render the Analisis column for Evaluasi Diri DataTables.
     */
    function pemutuDtColAnalisisEd($row)
    {
        $pivot = null;
        if (isset($row->orgUnits) && ! is_string($row->orgUnits) && $row->orgUnits->isNotEmpty()) {
            $pivot = $row->orgUnits->first()->pivot;
        } elseif (isset($row->ed_analisis) || isset($row->indikorgunit_id)) {
            $pivot = $row;
        }

        $text = $pivot->ed_analisis ?? '-';
        $html = '<div style="max-height: 200px; overflow-y: auto;" class="mb-2">' . $text . '</div>';

        // Evidence items
        $evidenceHtml = '';
        if ($pivot) {
            $hasFile = ! empty($pivot->ed_attachment);

            $hasLinks   = false;
            $linksArray = [];
            if (! empty($pivot->ed_links)) {
                $decoded = json_decode($pivot->ed_links, true);
                if (is_array($decoded) && count($decoded) > 0) {
                    $hasLinks   = true;
                    $linksArray = $decoded;
                }
            }

            // 1. Show Skala first
            if (isset($pivot->ed_skala) && $pivot->ed_skala !== null && $pivot->ed_skala !== '') {
                $evidenceHtml .= '<span class="badge bg-primary text-white me-2 mb-1" title="Nilai Skala Capaian" data-bs-toggle="tooltip">Skala [' . e($pivot->ed_skala) . ']</span>';

                // Add pipeline if there are subsequent attachments/links
                if ($hasFile || $hasLinks) {
                    $evidenceHtml .= '<span class="text-muted mx-1 mb-1">|</span>';
                }
            }

            // 2. Show File Attachment
            if ($hasFile) {
                // In summary controller it might be ID directly.
                $itemId = $pivot->indikorgunit_id ?? null;
                if ($itemId) {
                    $url           = route('pemutu.evaluasi-diri.download', encryptId($itemId));
                    $evidenceHtml .= '<a href="' . $url . '" target="_blank" class="btn btn-sm btn-ghost-primary me-1 mb-1" title="Unduh File Pendukung" data-bs-toggle="tooltip"><i class="ti ti-file-download fs-3"></i></a>';
                }
            }

            // 3. Show External Links
            if ($hasLinks) {
                foreach ($linksArray as $link) {
                    $name          = htmlspecialchars($link['name'] ?? 'Tautan');
                    $url           = htmlspecialchars($link['url'] ?? '#');
                    $evidenceHtml .= '<a href="' . $url . '" target="_blank" class="btn btn-sm btn-ghost-info me-1 mb-1" title="' . $name . '" data-bs-toggle="tooltip"><i class="ti ti-link fs-3"></i></a>';
                }
            }
        }

        if ($evidenceHtml) {
            $html .= '<div class="d-flex flex-wrap align-items-center border-top pt-2">' . $evidenceHtml . '</div>';
        }

        return $html;
    }
}

if (! function_exists('pemutuDtColStatusPengend')) {
    /**
     * Render the Status column for Pengendalian DataTables.
     */
    function pemutuDtColStatusPengend($row)
    {
        $status = null;
        $statusAtsn = null;

        if (isset($row->orgUnits) && ! is_string($row->orgUnits) && $row->orgUnits->isNotEmpty()) {
            $pivot      = $row->orgUnits->first()->pivot;
            $status     = $pivot->pengend_status ?? null;
            $statusAtsn = $pivot->pengend_status_atsn ?? null;
        } else {
            $status     = $row->pengend_status ?? null;
            $statusAtsn = $row->pengend_status_atsn ?? null;
        }

        $map = [
            'tetap'       => ['label' => 'Dipertahankan', 'color' => 'success'],
            'penyesuaian' => ['label' => 'Disesuaikan', 'color' => 'warning'],
            'ditingkatkan' => ['label' => 'Ditingkatkan', 'color' => 'blue'],
            'nonaktif'    => ['label' => 'Di-nonaktifkan', 'color' => 'danger'],
        ];

        $html = '';
        if ($status && isset($map[$status])) {
            $m     = $map[$status];
            $html .= '<div class="d-flex flex-column gap-1">';
            $html .= '<span class="badge bg-' . $m['color'] . '-lt text-' . $m['color'] . '" title="Usulan Unit">' . $m['label'] . '</span>';
            
            // Show superior status if different or specifically confirmed
            if ($statusAtsn && isset($map[$statusAtsn])) {
                $mAtsn = $map[$statusAtsn];
                if ($statusAtsn !== $status) {
                    $html .= '<span class="badge bg-' . $mAtsn['color'] . ' text-white" title="Keputusan Atasan"><i class="ti ti-crown me-1"></i>' . $mAtsn['label'] . '</span>';
                } else {
                    $html .= '<span class="badge bg-secondary-lt text-secondary" style="font-size: 8px;"><i class="ti ti-check me-1"></i>Validated</span>';
                }
            }
            $html .= '</div>';
            return $html;
        }

        return '<span class="badge bg-secondary-lt text-secondary">Belum Diisi</span>';
    }
}

if (! function_exists('pemutuDtColEisenhower')) {
    /**
     * Render the Eisenhower Matrix column for Pengendalian DataTables.
     */
    function pemutuDtColEisenhower($row)
    {
        $important = null;
        $urgent    = null;

        if (isset($row->orgUnits) && ! is_string($row->orgUnits) && $row->orgUnits->isNotEmpty()) {
            $pivot     = $row->orgUnits->first()->pivot;
            $important = $pivot->pengend_important_matrix ?? null;
            $urgent    = $pivot->pengend_urgent_matrix ?? null;
        } else {
            $important = $row->pengend_important_matrix ?? null;
            $urgent    = $row->pengend_urgent_matrix ?? null;
        }

        $importantBadge = match ($important) {
            'important'     => '<span class="badge bg-red-lt text-red">Important</span>',
            'not_important' => '<span class="badge bg-secondary-lt text-secondary">Not Imp.</span>',
            default         => '<span class="badge bg-light text-muted">-</span>',
        };
        $urgentBadge = match ($urgent) {
            'urgent'     => '<span class="badge bg-orange-lt text-orange">Urgent</span>',
            'not_urgent' => '<span class="badge bg-secondary-lt text-secondary">Not Urgent</span>',
            default      => '<span class="badge bg-light text-muted">-</span>',
        };

        return '<div class="d-flex flex-column gap-1">' . $importantBadge . $urgentBadge . '</div>';
    }
}

if (! function_exists('pemutuDtColAnalisisPengend')) {
    /**
     * Render the Analisis column for Pengendalian DataTables.
     */
    function pemutuDtColAnalisisPengend($row)
    {
        $analisis = null;
        if (isset($row->orgUnits) && ! is_string($row->orgUnits) && $row->orgUnits->isNotEmpty()) {
            $analisis = $row->orgUnits->first()->pivot->pengend_analisis ?? null;
        } else {
            $analisis = $row->pengend_analisis ?? null;
        }

        if (! $analisis || $analisis === '-') {
            return '<span class="text-muted small fst-italic">Belum diisi</span>';
        }
        // Strip HTML tags dan truncate
        $plain   = strip_tags($analisis);
        $preview = mb_strlen($plain) > 80 ? mb_substr($plain, 0, 80) . '…' : $plain;
        return '<span class="small text-muted" title="' . e($plain) . '">' . e($preview) . '</span>';
    }
}

if (! function_exists('pemutuDtColLabelsList')) {
    /**
     * Render the Labels as a flex-wrap container list (for summary/detail separate column).
     */
    function pemutuDtColLabelsList($row)
    {
        $html     = '<div class="d-flex flex-wrap gap-1">';
        $hasLabel = false;

        // Support concatenated label_details format (name|color, name|color)
        if (isset($row->label_details) && $row->label_details !== '-') {
            $labels = explode(', ', $row->label_details);
            foreach ($labels as $label) {
                if (strpos($label, '|') !== false) {
                    [$name, $color]  = explode('|', $label);
                    $html           .= '<span class="status status-' . e($color) . '">' . e($name) . '</span>';
                    $hasLabel        = true;
                }
            }
        }
        // Support all_labels and all_label_colors format (from IndikatorSummary model view)
        elseif (isset($row->all_labels) && $row->all_labels !== '') {
            $names  = explode(', ', $row->all_labels);
            $colors = explode(', ', $row->all_label_colors ?? '');

            foreach ($names as $index => $name) {
                $color     = $colors[$index] ?? 'secondary';
                $html     .= '<span class="status status-' . e($color) . '">' . e($name) . '</span>';
                $hasLabel  = true;
            }
        }
        // Support Eloquent related models format
        elseif (isset($row->labels) && ! is_string($row->labels) && $row->labels->isNotEmpty()) {
            foreach ($row->labels as $labelObj) {
                // Determine name and color. The data might be an IndikatorLabel or just Label model.
                $name      = $labelObj->name ?? $labelObj->label?->name;
                $color     = $labelObj->color ?? $labelObj->label?->color ?? 'secondary';
                $html     .= '<span class="status status-' . e($color) . '">' . e($name) . '</span>';
                $hasLabel  = true;
            }
        }

        $html .= '</div>';

        return $hasLabel ? $html : '<span class="text-muted fst-italic small">-</span>';
    }
}

if (! function_exists('pemutuDtColStatusEd')) {
    /**
     * Render the Status of Evaluasi Diri (ED) for DataTables.
     */
    function pemutuDtColStatusEd($row)
    {
        $pivot = null;
        if (isset($row->orgUnits)) {
            $pivot = $row->orgUnits->first()?->pivot;
        }

        $edCapaian = $pivot->ed_capaian ?? $row->ed_capaian ?? null;
        $edSkala   = $pivot->ed_skala ?? $row->ed_skala ?? null;

        if ($edCapaian) {
            $skalaLabel = $edSkala !== null
                ? '<span class="badge bg-blue-lt text-blue ms-1">Skala ' . e($edSkala) . '</span>'
                : '';

            return '<span class="badge bg-success-lt text-success"><i class="ti ti-check me-1"></i>ED Diisi</span>' . $skalaLabel;
        }

        return '<span class="badge bg-secondary-lt text-secondary">Belum Diisi</span>';
    }
}

if (! function_exists('pemutuDtColStatusAmi')) {
    /**
     * Render the Status of AMI (Audit Mutu Internal) for DataTables.
     */
    function pemutuDtColStatusAmi($row)
    {
        $pivot = null;
        if (isset($row->orgUnits)) {
            $pivot = $row->orgUnits->first()?->pivot;
        }

        $amiHasil  = $pivot->ami_hasil_akhir ?? $row->ami_hasil_akhir ?? null;
        $label     = $row->ami_hasil_label ?? $row->ami_hasil_akhir_label ?? null;
        $amiTemuan = $pivot->ami_hasil_temuan ?? $row->ami_hasil_temuan ?? null;
        $amiRekom  = $pivot->ami_hasil_temuan_rekom ?? $row->ami_hasil_temuan_rekom ?? null;

        if ($amiHasil !== null) {
            $colors = [0 => 'danger', 1 => 'success', 2 => 'info', 'KTS' => 'danger', 'Terpenuhi' => 'success', 'Terlampaui' => 'info'];
            $color  = $colors[$amiHasil] ?? 'secondary';

            // Fallback label if not provided in row
            if (! $label) {
                if (is_numeric($amiHasil)) {
                    $labels = [0 => 'KTS', 1 => 'Terpenuhi', 2 => 'Terlampaui'];
                    $label  = $labels[$amiHasil] ?? '-';
                } else {
                    $label = $amiHasil;
                }
            }

            $html = '<div class="d-flex flex-column gap-1">';
            $html .= '<div><span class="badge bg-' . $color . '-lt text-' . $color . ' fs-6 px-2">' . e($label) . '</span></div>';

            if ($amiHasil == 0 && $amiRekom && $amiRekom !== '-') {
                $html .= '<div style="max-height: 100px; overflow-y: auto; scrollbar-width: thin;" class="text-muted pe-1">';
                $html .= '<span class="fw-bold d-block text-uppercase text-danger" style="font-size: 9px; opacity: 0.8;">Rekomendasi Auditor:</span>';
                $html .= '<div class="small lh-sm">' . e($amiRekom) . '</div>';
                $html .= '</div>';
            } elseif ($amiTemuan && $amiTemuan !== '-') {
                $excerpt  = \Str::limit($amiTemuan, 100);
                $html    .= '<div class="text-muted small italic" title="' . e($amiTemuan) . '">' . e($excerpt) . '</div>';
            }

            $html .= '</div>';
            return $html;
        }

        return '<span class="badge bg-warning-lt text-warning"><i class="ti ti-clock me-1"></i>Belum Dinilai</span>';
    }
}

    function pemutuDtColStatusPeningkatan($row)
    {
        $status = $row->prev_pengend_status_atsn ?? ($row->prev_ou->pengend_status_atsn ?? null);
        
        $map = [
            'tetap'        => ['label' => 'Dipertahankan', 'color' => 'success'],
            'penyesuaian'  => ['label' => 'Disesuaikan', 'color' => 'warning'],
            'ditingkatkan' => ['label' => 'Ditingkatkan', 'color' => 'blue'],
            'nonaktif'     => ['label' => 'Nonaktif', 'color' => 'danger'],
        ];

        if ($status && isset($map[$status])) {
            $m = $map[$status];
            return '<span class="badge bg-' . $m['color'] . '-lt text-' . $m['color'] . '">' . $m['label'] . '</span>';
        }

        return '<span class="badge bg-blue-lt">Dipertahankan</span>'; // Default for followed-up indicators
    }

if (! function_exists('pemutuDtColRtp')) {
    /**
     * Render the RTP column for AMI DataTables.
     */
    function pemutuDtColRtp($row)
    {
        $pivot = null;
        if (isset($row->orgUnits) && ! is_string($row->orgUnits) && $row->orgUnits->isNotEmpty()) {
            $pivot = $row->orgUnits->first()->pivot;
        } elseif (isset($row->ami_rtp_isi) || isset($row->indikorgunit_id)) {
            $pivot = $row;
        }

        if (! $pivot || empty($pivot->ami_rtp_isi)) {
            return '<span class="text-muted small fst-italic">-</span>';
        }

        $text = $pivot->ami_rtp_isi;
        $tgl  = $pivot->ami_rtp_tgl_pelaksanaan ? formatTanggalIndo($pivot->ami_rtp_tgl_pelaksanaan) : '-';

        $html = '<div class="d-flex flex-column gap-2">';
        $html .= '<div style="max-height: 150px; overflow-y: auto; scrollbar-width: thin;" class="pe-1 small lh-base">' . $text . '</div>';
        $html .= '<div class="mt-auto pt-1 border-top" style="font-size: 10px;">';
        $html .= '<span class="text-muted text-uppercase fw-bold"><i class="ti ti-calendar-event me-1"></i>Pelaksanaan:</span>';
        $html .= '<span class="ms-1 fw-semibold text-primary">' . e($tgl) . '</span>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}

if (! function_exists('pemutuDefaultSubDocuments')) {
    /**
     * Get default sub-documents (points) for a document type.
     */
    function pemutuDefaultSubDocuments($jenis): array
    {
        $points = [
            'Visi, Misi dan Tujuan',
            'Rasional Standar',
            'Definisi Istilah',
            'Pihak yang Bertanggungjawab',
            'Pernyataan Isi Standar / Indikator Capaian',
            'Strategi Pelaksanaan',
            'Dokumen Terkait',
            'Referensi',
        ];

        $jenis = strtolower(trim($jenis));

        if ($jenis === 'kebijakan') {
            unset($points[4]); // Remove "Pernyataan Isi Standar / Indikator Capaian" (index 4 is No 5)
            return array_values($points);
        }

        if ($jenis === 'standar') {
            return $points;
        }

        return [];
    }
}
