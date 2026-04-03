<?php

use App\Config\PemutuDokumenConfig;

/**
 * Pemutu Helper Functions - DEPRECATED
 *
 * These functions are kept for backward compatibility only.
 * Please use PemutuDokumenConfig class directly in new code:
 *
 *   OLD: pemutuJenisLabel('standar')
 *   NEW: PemutuDokumenConfig::for('standar')->label()
 *
 *   OLD: pemutuMappableJenis('misi')
 *   NEW: PemutuDokumenConfig::for('misi')->mappableTo()
 *
 *   OLD: pemutuDefaultSubDocuments('standar')
 *   NEW: PemutuDokumenConfig::for('standar')->getDefaultPoin()
 */
if (! function_exists('pemutuJenisLabel')) {
    /**
     * @deprecated Use PemutuDokumenConfig::for($jenis)->label() instead
     */
    function pemutuJenisLabel($jenis): string
    {
        return App\Config\PemutuDokumenConfig::for($jenis)->label();
    }
}

if (! function_exists('pemutuJenisLabelFull')) {
    /**
     * @deprecated Use PemutuDokumenConfig::for($jenis)->labelFull() instead
     */
    function pemutuJenisLabelFull($jenis): string
    {
        return App\Config\PemutuDokumenConfig::for($jenis)->labelFull();
    }
}

if (! function_exists('pemutuTabByJenis')) {
    /**
     * @deprecated Use PemutuDokumenConfig::for($jenis)->category() instead
     */
    function pemutuTabByJenis($jenis): string
    {
        return App\Config\PemutuDokumenConfig::for($jenis)->category();
    }
}

if (! function_exists('pemutuChildLabel')) {
    /**
     * Get label for child elements based on parent document type.
     *
     * @deprecated Will be replaced in future versions
     */
    function pemutuChildLabel($jenis): string
    {
        return match (strtolower(trim($jenis))) {
            'visi', 'misi', 'standar' => 'Poin',
            'rjp', 'renstra', 'renop', 'kebijakan' => 'Indikator',
            'formulir' => 'Mapping',
            default    => 'Turunan'
        };
    }
}

if (! function_exists('pemutuIsDokSubBased')) {
    /**
     * Check if document type uses Sub-Documents (DokSub) for its children.
     *
     * @deprecated Will be replaced in future versions
     */
    function pemutuIsDokSubBased($jenis): bool
    {
        return in_array(strtolower(trim($jenis)), [
            'standar', 'manual_prosedur',
            'visi', 'misi', 'rjp', 'renstra', 'kebijakan',
        ]);
    }
}

if (! function_exists('pemutuMappableJenis')) {
    /**
     * @deprecated Use PemutuDokumenConfig::for($jenis)->mappableTo() instead
     */
    function pemutuMappableJenis($jenis): ?array
    {
        return App\Config\PemutuDokumenConfig::for($jenis)->mappableTo();
    }
}

if (! function_exists('pemutuDefaultSubDocuments')) {
    /**
     * @deprecated Use PemutuDokumenConfig::for($jenis)->getDefaultPoin() instead
     */
    function pemutuDefaultSubDocuments($jenis): array
    {
        return App\Config\PemutuDokumenConfig::for($jenis)->getDefaultPoin();
    }
}

if (! function_exists('pemutuFixedJenis')) {
    /**
     * Get the next document type in the hierarchy chain.
     *
     * @deprecated Will be replaced in future versions
     */
    function pemutuFixedJenis($jenis): ?string
    {
        return match (strtolower(trim($jenis))) {
            'kebijakan'       => 'standar',
            'standar'         => 'manual_prosedur',
            'manual_prosedur' => 'formulir',
            'visi'            => 'misi',
            'misi'            => 'rjp',
            'rjp'             => 'renstra',
            'renstra'         => 'renop',
            default           => null,
        };
    }
}

if (! function_exists('pemutuIndikatorTypeInfo')) {
    /**
     * Get label and color for indicator type.
     *
     * @deprecated Will be replaced in future versions
     */
    function pemutuIndikatorTypeInfo($type): array
    {
        $data = [
            'standar'  => ['color' => 'primary', 'label' => 'Indikator Standar', 'short-label' => 'ISTD'],
            'renop'    => ['color' => 'purple', 'label' => 'Indikator Renop', 'short-label' => 'IRNP'],
            'performa' => ['color' => 'success', 'label' => 'Indikator Performa', 'short-label' => 'IPRF'],
        ];

        return $data[strtolower(trim($type))] ?? ['color' => 'secondary', 'label' => ucfirst($type ?? '-'), 'short-label' => 'IND'];
    }
}

if (! function_exists('pemutuKebijakanJenisList')) {
    /**
     * Get the ordered list of kebijakan document types.
     *
     * @deprecated Use PemutuDokumenConfig::all() instead
     */
    function pemutuKebijakanJenisList(): array
    {
        return ['kebijakan', 'standar', 'manual_prosedur', 'formulir', 'visi', 'misi', 'rjp', 'renstra', 'renop'];
    }
}

if (! function_exists('pemutuTreeBasedTypes')) {
    /**
     * @deprecated Use PemutuDokumenConfig::treeBasedTypes() instead
     */
    function pemutuTreeBasedTypes(): array
    {
        return App\Config\PemutuDokumenConfig::treeBasedTypes();
    }
}

if (! function_exists('pemutuIndikatorGenerators')) {
    /**
     * @deprecated Use PemutuDokumenConfig::indikatorGeneratorTypes() instead
     */
    function pemutuIndikatorGenerators(): array
    {
        return App\Config\PemutuDokumenConfig::indikatorGeneratorTypes();
    }
}

// ─────────────────────────────────────────────────────────
// HELPER FUNCTIONS THAT ARE STILL NEEDED (Not in Config)
// ─────────────────────────────────────────────────────────

if (! function_exists('pemutuSkalaBadge')) {
    /**
     * Render a standardized color-coded scale badge.
     */
    function pemutuSkalaBadge($skala, $size = 'md', $showText = true): string
    {
        if ($skala === null || $skala === '') {
            return '<span class="text-muted-light small fst-italic">n/a</span>';
        }

        $score = (int) $skala;
        $color = match (true) {
            $score >= 4 => 'green',
            $score >= 3 => 'blue',
            $score >= 2 => 'orange',
            $score >= 1 => 'red',
            default     => 'secondary',
        };

        $fontSize   = $size === 'sm' ? 'font-size: 10px;' : 'font-size: 11px;';
        $badgeClass = $size === 'sm' ? 'badge-sm' : '';
        $text       = $showText ? 'Skala ' . $score : $score;

        return '<span class="badge bg-' . $color . '-lt text-' . $color . ' ' . $badgeClass . ' border border-' . $color . ' border-opacity-10 fw-bold px-2" style="' . $fontSize . '" data-bs-toggle="tooltip" title="Skala Capaian: ' . $score . '">' . e($text) . '</span>';
    }
}

if (! function_exists('pemutuLabelBadge')) {
    /**
     * Render a single label badge HTML with correct color from its LabelType.
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
     * Render multiple label badges HTML.
     */
    function pemutuLabelBadges($labels, string $style = 'lt'): string
    {
        $badges = [];
        foreach ($labels as $label) {
            $badges[] = pemutuLabelBadge($label, $style);
        }

        return implode(' ', $badges);
    }
}

if (! function_exists('pemutuDtColNo')) {
    /**
     * Render the first column (# / No) for Indikator DataTables.
     */
    function pemutuDtColNo($row)
    {
        $kel  = $row->kelompok_indikator ?? null;
        $jd   = $row->jenis_data ?? '-';
        $base = $row->parent_no_indikator ?? ($row->parent?->no_indikator ?? null);

        $html  = '<div class="text-center small d-flex flex-column gap-1 align-items-center">';
        $html .= $kel ? '<div class="text-muted mt-1">' . e($kel) . '</div>' : '';
        $html .= pemutuDtColEisenhower($row);
        $html .= $jd !== '-' ? '<span class="badge ">' . e($jd) . '</span>' : '';
        $html .= $base ? '<div class="text-muted mt-1">Base: [' . e($base) . ']</div>' : '';
        $html .= '</div>';

        return $html;
    }
}

if (! function_exists('pemutuDtColIndikator')) {
    /**
     * Render the second column (Indikator Content) for Indikator DataTables.
     */
    function pemutuDtColIndikator($row)
    {
        // 1. Standar Header
        $standar = '-';
        if (isset($row->doksub_details) && $row->doksub_details !== '-') {
            $ds  = $row->dokSubs->first();
            $doc = $ds->dokumen ?? null;
            // Get root/parent doc if exists (e.g. Formulir -> Standar)
            $induk = ($doc && $doc->parent) ? $doc->parent : $doc;
            if ($induk) {
                $standar = $induk->judul;
            }
        } elseif (isset($row->dokumen_judul)) {
            $standar = $row->dokumen_judul;
        } elseif (isset($row->dokSubs) && $row->dokSubs->isNotEmpty()) {
            $ds  = $row->dokSubs->first();
            $doc = $ds->dokumen ?? null;
            // Find root/main document
            $induk = ($doc && $doc->parent) ? $doc->parent : $doc;
            if ($induk) {
                $standar = $induk->judul;
            } else {
                // If no document found, fallback to sub-document title
                $standar = $ds->judul;
            }
        }

        if (isset($doc) && $doc) {
            $standar .= ' (' . $doc->periode . ')';
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
        $html .= '<div class="text-muted small fw-bold opacity-75">' . e($standar) . '</div>';

        $no    = $row->no_indikator ?? '-';
        $html .= '<div>';
        $html .= '<span class="text-primary fw-bold me-1">[' . e($no) . ']</span>';
        $html .= '<span class="fw-medium lh-base">' . e($text) . '</span>';
        $html .= '</div>';

        if ($labelHtml) {
            $html .= '<div class="mb-1 small">' . $labelHtml . '</div>';
        }

        if ($resp) {
            $html .= '<div class="small text-muted d-flex align-items-center gap-1 opacity-75">';
            $html .= '<i class="ti ti-building"></i>' . e($resp);
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

        $html = '<div class="fw-bold">' . e($target) . '</div>';

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
        $html = pemutuTextScroll($text);

        // Evidence items
        $evidenceHtml = '';
        if ($pivot) {
            $itemId   = $pivot->indikorgunit_id ?? null;
            $indModel = $itemId ? \App\Models\Pemutu\IndikatorOrgUnit::with('media')->find($itemId) : null;
            $hasFile  = $indModel ? $indModel->hasMedia('ed_attachments') : false;

            $hasLinks   = false;
            $linksArray = [];
            if (! empty($pivot->ed_links)) {
                $decoded = json_decode($pivot->ed_links, true);
                if (is_array($decoded) && count($decoded) > 0) {
                    $hasLinks   = true;
                    $linksArray = $decoded;
                }
            }

            // 1. Show File Attachment
            if ($hasFile && isset($indModel)) {
                foreach ($indModel->getMedia('ed_attachments') as $media) {
                    $evidenceHtml .= '<a href="' . $media->getUrl() . '" target="_blank" class="btn btn-sm btn-ghost-primary me-1 mb-1" title="Unduh: ' . e($media->file_name) . '" data-bs-toggle="tooltip"><i class="ti ti-file-download fs-3"></i></a>';
                }
            }

            // 2. Show External Links
            if ($hasLinks) {
                foreach ($linksArray as $link) {
                    $name          = htmlspecialchars($link['name'] ?? 'Tautan');
                    $url           = htmlspecialchars($link['url'] ?? '#');
                    $evidenceHtml .= '<a href="' . $url . '" target="_blank" class="btn btn-sm btn-ghost-info me-1 mb-1" title="' . $name . '" data-bs-toggle="tooltip"><i class="ti ti-link fs-3"></i></a>';
                }
            }
        }

        if ($evidenceHtml) {
            $html .= '<div class="d-flex flex-wrap align-items-center border-top pt-2 mt-2">' . $evidenceHtml . '</div>';
        }

        return $html;
    }
}

if (! function_exists('pemutuDtColCapaianSkalaEd')) {
    /**
     * Render the Capaian + Skala grouped result column for ED.
     */
    function pemutuDtColCapaianSkalaEd($row)
    {
        $pivot = null;
        if (isset($row->orgUnits) && ! is_string($row->orgUnits) && $row->orgUnits->isNotEmpty()) {
            $pivot = $row->orgUnits->first()->pivot;
        } elseif (isset($row->ed_capaian) || isset($row->indikorgunit_id)) {
            $pivot = $row;
        }

        if (! $pivot) {
            return '<span class="text-muted fst-italic">Belum diisi</span>';
        }

        $capaian = $pivot->ed_capaian ?? '-';
        $skala   = $pivot->ed_skala ?? null;

        $html  = '<div class="text-center">';
        $html .= '<div class="fw-bold mb-1">' . e($capaian) . '</div>';

        if ($skala !== null && $skala !== '') {
            $html .= '<div>' . pemutuSkalaBadge($skala) . '</div>';
        }
        $html .= '</div>';

        return $html;
    }
}

if (! function_exists('pemutuDtColStatusPengend')) {
    /**
     * Render the Status column for Pengendalian DataTables.
     */
    function pemutuDtColStatusPengend($row)
    {
        $status     = null;
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
            'tetap'        => ['label' => 'Dipertahankan', 'color' => 'success'],
            'penyesuaian'  => ['label' => 'Disesuaikan', 'color' => 'warning'],
            'ditingkatkan' => ['label' => 'Ditingkatkan', 'color' => 'blue'],
            'nonaktif'     => ['label' => 'Di-nonaktifkan', 'color' => 'danger'],
        ];

        $html = '';
        if ($status && isset($map[$status])) {
            $m     = $map[$status];
            $html .= '<div class="d-flex flex-column gap-1">';
            $html .= '<span class="badge bg-' . $m['color'] . '-lt text-' . $m['color'] . '" title="Usulan Unit">' . $m['label'] . '</span>';

            if ($statusAtsn && isset($map[$statusAtsn])) {
                $mAtsn = $map[$statusAtsn];
                if ($statusAtsn !== $status) {
                    $html .= '<span class="badge bg-' . $mAtsn['color'] . ' text-white" title="Keputusan Atasan"><i class="ti ti-crown me-1"></i>' . $mAtsn['label'] . '</span>';
                } else {
                    $html .= '<span class="badge bg-secondary-lt text-secondary text-small"><i class="ti ti-check me-1"></i>Validated</span>';
                }
            }
            $html .= '</div>';

            return $html;
        }

        return '<span class="badge bg-secondary-lt text-secondary">Belum Diisi</span>';
    }
}

if (! function_exists('pemutuDtColEisenhower')) {
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
        $plain   = strip_tags($analisis);
        $preview = mb_strlen($plain) > 80 ? mb_substr($plain, 0, 80) . '…' : $plain;

        return '<span class="small text-muted" title="' . e($plain) . '">' . e($preview) . '</span>';
    }
}

if (! function_exists('pemutuDtColLabelsList')) {
    function pemutuDtColLabelsList($row)
    {
        $html     = '<div class="d-flex flex-wrap gap-1">';
        $hasLabel = false;

        if (isset($row->label_details) && $row->label_details !== '-') {
            $labels = explode(', ', $row->label_details);
            foreach ($labels as $label) {
                if (strpos($label, '|') !== false) {
                    [$name, $color]  = explode('|', $label);
                    $html           .= '<span class="status status-' . e($color) . '">' . e($name) . '</span>';
                    $hasLabel        = true;
                }
            }
        } elseif (isset($row->all_labels) && $row->all_labels !== '') {
            $names  = explode(', ', $row->all_labels);
            $colors = explode(', ', $row->all_label_colors ?? '');

            foreach ($names as $index => $name) {
                $color     = $colors[$index] ?? 'secondary';
                $html     .= '<span class="status status-' . e($color) . '">' . e($name) . '</span>';
                $hasLabel  = true;
            }
        } elseif (isset($row->labels) && ! is_string($row->labels) && $row->labels->isNotEmpty()) {
            foreach ($row->labels as $labelObj) {
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
    function pemutuDtColStatusEd($row)
    {
        $pivot = null;
        if (isset($row->orgUnits)) {
            $pivot = $row->orgUnits->first()?->pivot;
        }

        $edCapaian  = $pivot->ed_capaian ?? $row->ed_capaian ?? null;
        $edAnalisis = $pivot->ed_analisis ?? $row->ed_analisis ?? null;
        $edSkala    = $pivot->ed_skala ?? $row->ed_skala ?? null;

        if ($edCapaian || $edAnalisis) {
            $html = '';
            if ($edSkala !== null && $edSkala !== '') {
                $html .= '<div>' . pemutuSkalaBadge($edSkala, 'sm') . '</div>';
            }

            $content = '';
            if ($edCapaian) {
                $content .= '<strong>Capaian:</strong><br>' . nl2br(e($edCapaian));
            }
            if ($edAnalisis) {
                if ($content) {
                    $content .= '<div class="mt-2 text-muted-dark opacity-50 ms-n1">-------------------</div>';
                }
                $content .= '<strong>Analisis Capaian:</strong><br>' . nl2br(e($edAnalisis));
            }

            $html .= pemutuTextScroll($content, '150px');

            return $html;
        }

        return '<span class="badge bg-secondary-lt text-secondary">Belum Diisi</span>';
    }
}

if (! function_exists('pemutuDtColStatusAmi')) {
    function pemutuDtColStatusAmi($row)
    {
        $pivot = null;
        if (isset($row->orgUnits)) {
            $pivot = $row->orgUnits->first()?->pivot;
        }

        $amiHasil = $pivot->ami_hasil_akhir ?? $row->ami_hasil_akhir ?? null;
        $label    = $row->ami_hasil_label ?? $row->ami_hasil_akhir_label ?? null;

        if ($amiHasil !== null) {
            $colors = [0 => 'danger', 1 => 'success', 2 => 'info', 'KTS' => 'danger', 'Terpenuhi' => 'success', 'Terlampaui' => 'info'];
            $icons  = [0 => 'ti-alert-triangle', 1 => 'ti-check', 2 => 'ti-rocket', 'KTS' => 'ti-alert-triangle', 'Terpenuhi' => 'ti-check', 'Terlampaui' => 'ti-rocket'];
            $color  = $colors[$amiHasil] ?? 'secondary';
            $icon   = $icons[$amiHasil] ?? 'ti-help';

            if (! $label) {
                if (is_numeric($amiHasil)) {
                    $labels = [0 => 'KTS', 1 => 'Terpenuhi', 2 => 'Terlampaui'];
                    $label  = $labels[$amiHasil] ?? '-';
                } else {
                    $label = $amiHasil;
                }
            }

            return '<span class="badge bg-' . $color . '-lt text-' . $color . ' fs-6 px-2"><i class="' . $icon . ' me-1"></i>' . e($label) . '</span>';
        }

        return '<span class="badge bg-warning-lt text-warning"><i class="ti ti-clock me-1"></i>Belum Dinilai</span>';
    }
}

if (! function_exists('pemutuDtColStatusPeningkatan')) {
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

        return '<span class="badge bg-blue-lt">Dipertahankan</span>';
    }
}

if (! function_exists('pemutuDtColRtp')) {
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

        $html  = '<div class="d-flex flex-column gap-2">';
        $html .= pemutuTextScroll($text);
        $html .= '<div class="mt-auto pt-1 border-top" style="font-size: 10px;">';
        $html .= '<span class="text-muted text-uppercase fw-bold"><i class="ti ti-calendar-event me-1"></i>Pelaksanaan:</span>';
        $html .= '<span class="ms-1 fw-semibold text-primary">' . e($tgl) . '</span>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}

if (! function_exists('pemutuPeriodeStatus')) {
    /**
     * Get human readable period standing (active, ended, upcoming).
     *
     * @param  \Carbon\Carbon|string|null  $startDate
     * @param  \Carbon\Carbon|string|null  $endDate
     */
    function pemutuPeriodeStatus($startDate, $endDate): array
    {
        if (! $startDate || ! $endDate) {
            return [
                'is_active'   => false,
                'status_text' => 'Jadwal Belum Diatur',
                'time_info'   => 'Tidak ada rentang waktu tersedia',
                'color'       => 'warning',
            ];
        }

        $now   = \Carbon\Carbon::now()->startOfDay();
        $start = \Carbon\Carbon::parse($startDate)->startOfDay();
        $end   = \Carbon\Carbon::parse($endDate)->endOfDay();

        if ($now->lt($start)) {
            $diffDays = $now->diffInDays($start);

            return [
                'is_active'   => false,
                'status_text' => 'Belum Mulai',
                'color'       => 'secondary',
            ];
        } elseif ($now->gt($end)) {
            $diffDays = $end->copy()->startOfDay()->diffInDays($now);

            return [
                'is_active'   => false,
                'status_text' => 'Telah Berakhir',
                'color'       => 'danger',
            ];
        } else {
            $diffDays = $now->diffInDays($end->copy()->startOfDay());

            return [
                'is_active'   => true,
                'status_text' => 'Sedang Berjalan',
                'color'       => 'success',
            ];
        }
    }
}

if (! function_exists('pemutuTextScroll')) {
    /**
     * Standardized scrollable text container for DataTables.
     */
    function pemutuTextScroll($text, $maxHeight = '120px'): string
    {
        if (empty($text) || $text === '-') {
            return '<span class="text-muted small fst-italic">-</span>';
        }

        return '<div style="max-height: ' . $maxHeight . '; overflow-y: auto; scrollbar-width: thin; line-height: 1.5;" class="pe-1 small">' . $text . '</div>';
    }
}

if (! function_exists('applySpmiDatatableSearch')) {
    /**
     * Apply the standard DataTable search filter for SPMI indikator queries.
     * Centralizes the repeated search block used across ED, AMI, Pengendalian, and Indikator controllers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    function applySpmiDatatableSearch($query, $request)
    {
        if ($request->filled('search')) {
            $searchValue = $request->input('search.value') ?? $request->input('search');
            $search      = is_array($searchValue) ? ($searchValue['value'] ?? '') : (string) $searchValue;
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('no_indikator', 'LIKE', "%{$search}%")
                        ->orWhere('indikator', 'LIKE', "%{$search}%")
                        ->orWhereHas('orgUnits', function ($sq) use ($search) {
                            $sq->where('hr_struktur_organisasi.name', 'LIKE', "%{$search}%")
                                ->orWhere('hr_struktur_organisasi.code', 'LIKE', "%{$search}%");
                        });
                });
            }
        }

        return $query;
    }
}

if (! function_exists('parseSpmiFilters')) {
    /**
     * Parse SPMI request filters with standard 'all'/empty handling and ID decryption.
     * Centralizes the repeated filter parsing block used across ED, AMI, Pengendalian controllers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $keys  Keys to extract from the request
     * @param  array  $decryptKeys  Keys whose values should be decrypted (default: unit_id, dok_id, orgunit_id)
     */
    function parseSpmiFilters($request, array $keys, array $decryptKeys = ['unit_id', 'dok_id', 'orgunit_id']): array
    {
        $filters = [];
        foreach ($request->only($keys) as $key => $value) {
            if ($value !== null && $value !== '' && $value !== 'all') {
                $filters[$key] = in_array($key, $decryptKeys) ? decryptIdIfEncrypted($value) : $value;
            }
        }

        return $filters;
    }
}

if (! function_exists('pemutuPpeppConfig')) {
    /**
     * Get the standardized visual configuration for PPEPP stages.
     *
     * @param  string  $type  The specific phase or sub-phase (e.g. 'penetapan', 'ed', 'ami', 'te', 'rtp', 'ptp', 'pengendalian', 'peningkatan')
     * @return array Configuration containing label, color, and icon.
     */
    function pemutuPpeppConfig(string $type): array
    {
        return match ($type) {
            'penetapan', 'indikator', 'dokumen' => [
                'ppepp'       => 'P',
                'label'       => 'Penetapan',
                'color'       => 'azure',
                'icon'        => 'target',
                'date_prefix' => 'penetapan',
            ],
            'pelaksanaan'  => [
                'ppepp'       => 'P',
                'label'       => 'Pelaksanaan',
                'color'       => 'yellow',
                'icon'        => 'tools',
                'date_prefix' => 'penetapan', // Implementation usually follows the standard setting window
            ],
            'ed', 'ami', 'te', 'rtp', 'ptp', 'evaluasi' => [
                'ppepp'       => 'E',
                'label'       => 'Evaluasi',
                'color'       => 'orange',
                'icon'        => 'checklist',
                'date_prefix' => 'ed', // PTP and AMI/TE usually share the ED/Evaluasi window in this project
            ],
            'pengendalian' => [
                'ppepp'       => 'P',
                'label'       => 'Pengendalian',
                'color'       => 'red',
                'icon'        => 'settings-check',
                'date_prefix' => 'pengendalian',
            ],
            'peningkatan'  => [
                'ppepp'       => 'P',
                'label'       => 'Peningkatan',
                'color'       => 'pink',
                'icon'        => 'trending-up',
                'date_prefix' => 'peningkatan',
            ],
            default        => [
                'ppepp'       => '?',
                'label'       => 'SPMI',
                'color'       => 'azure',
                'icon'        => 'calendar-event',
                'date_prefix' => 'penetapan',
            ],
        };
    }
}
