<?php
namespace App\Services\Pemutu;

use App\Models\Pemutu\Indikator;
use App\Models\Pemutu\IndikatorOrgUnit;
use App\Models\Pemutu\PeriodeSpmi;
use App\Models\Hr\StrukturOrganisasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;

class AmiExportService
{
    public function __construct(
        protected IndikatorService $indikatorService
    ) {}

    /**
     * Export PTK (Penemuan Temuan dan Ketidaksesuaian) - DOCX
     * Mengikuti pola TestController: direct PhpWord generation tanpa template
     */
    public function exportPtk(PeriodeSpmi $periode, ?int $unitId = null, ?int $dokId = null, ?string $edStatus = null)
    {
        $filters = [
            'dok_id'          => $dokId ? encryptId($dokId) : null,
            'ed_status'       => $edStatus,
            'ami_hasil_akhir' => 0, // KTS
        ];

        $ktsIndicators = $this->indikatorService->getIndikatorOrgUnitSpmiQuery($periode, $unitId, $filters)
            ->orderBy('pemutu_indikator_orgunit.org_unit_id')
            ->orderBy('pemutu_indikator.no_indikator')
            ->get();

        if ($ktsIndicators->isEmpty()) {
            throw new \App\Exceptions\DataNotFoundException('Tidak ada indikator KTS untuk diekspor.');
        }

        // Get unit name if filtered
        $unitName = $unitId ? StrukturOrganisasi::find($unitId)?->name : 'Semua Unit';
        $dokumenName = $dokId ? \App\Models\Pemutu\Dokumen::find($dokId)?->judul : 'Semua Dokumen';

        // Create PhpWord document
        $phpWord = new PhpWord();

        // Add styles
        $phpWord->addTitleStyle(1, 
            ['size' => 16, 'bold' => true, 'color' => '000000'], 
            ['spaceAfter' => 200, 'alignment' => 'center']
        );
        $phpWord->addTitleStyle(2, 
            ['size' => 14, 'bold' => true, 'color' => '000000'], 
            ['spaceAfter' => 150]
        );
        $phpWord->addFontStyle('normalText', ['size' => 11]);
        $phpWord->addParagraphStyle('normalText', ['spaceAfter' => 100, 'lineHeight' => 1.5]);
        $phpWord->addFontStyle('headerText', ['size' => 11, 'bold' => true]);

        // Add section with margins
        $section = $phpWord->addSection([
            'marginTop' => 1440,
            'marginBottom' => 1440,
            'marginLeft' => 1440,
            'marginRight' => 1440,
        ]);

        // Header - Title
        $section->addText('PENEMUAN TEMUAN DAN KETIDAKSESUAIAN (PTK)', 
            ['size' => 16, 'bold' => true], 
            ['alignment' => 'center', 'spaceAfter' => 200]
        );
        $section->addTextBreak(1);

        // Info block
        $section->addText('Periode: ' . ($periode->nama_periode ?? 'Periode ' . $periode->periode), 'normalText', 'normalText');
        $section->addText('Jenis: ' . ucfirst($periode->jenis_periode ?? 'SPMI'), 'normalText', 'normalText');
        $section->addText('Unit: ' . $unitName, 'normalText', 'normalText');
        $section->addText('Dokumen: ' . $dokumenName, 'normalText', 'normalText');
        $section->addText('Tanggal Cetak: ' . formatTanggalIndo(now()), 'normalText', 'normalText');
        $section->addText('Total Temuan: ' . count($ktsIndicators), 'normalText', 'normalText');
        $section->addTextBreak(2);

        // Table header
        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 80,
            'width' => 10000,
        ]);

        // Table headers
        $headers = [
            ['text' => 'No', 'width' => 500],
            ['text' => 'No Ind', 'width' => 1000],
            ['text' => 'Indikator', 'width' => 3000],
            ['text' => 'Unit', 'width' => 2000],
            ['text' => 'Temuan', 'width' => 3000],
            ['text' => 'Akar Sebab', 'width' => 3000],
            ['text' => 'Akibat', 'width' => 3000],
            ['text' => 'Rekomendasi', 'width' => 3000],
            ['text' => 'Jadwal', 'width' => 1500],
        ];

        $headerRow = $table->addRow();
        foreach ($headers as $header) {
            $cell = $headerRow->addCell($header['width'], ['valign' => 'center']);
            $cell->addText($header['text'], ['bold' => true], ['alignment' => 'center']);
        }

        // Table rows
        foreach ($ktsIndicators as $index => $indOrg) {
            $indikator = $indOrg->indikator;
            $row = $table->addRow();

            $row->addCell(500, ['valign' => 'center'])
                ->addText($index + 1, 'normalText', ['alignment' => 'center']);
            
            $row->addCell(1000, ['valign' => 'center'])
                ->addText($indikator->no_indikator ?? '-', 'normalText', ['alignment' => 'center']);
            
            $row->addCell(3000, ['valign' => 'center'])
                ->addText($indikator->indikator ?? '-', 'normalText');
            
            $row->addCell(2000, ['valign' => 'center'])
                ->addText($indOrg->orgUnit->name ?? '-', 'normalText');
            
            $row->addCell(3000, ['valign' => 'center'])
                ->addText(strip_tags($indOrg->ami_hasil_temuan ?? '-'), 'normalText');
            
            $row->addCell(3000, ['valign' => 'center'])
                ->addText(strip_tags($indOrg->ami_hasil_temuan_sebab ?? '-'), 'normalText');
            
            $row->addCell(3000, ['valign' => 'center'])
                ->addText(strip_tags($indOrg->ami_hasil_temuan_akibat ?? '-'), 'normalText');
            
            $row->addCell(3000, ['valign' => 'center'])
                ->addText(strip_tags($indOrg->ami_rtp_isi ?? '-'), 'normalText');
            
            $row->addCell(1500, ['valign' => 'center'])
                ->addText(
                    $indOrg->ami_rtp_tgl_pelaksanaan 
                        ? formatTanggalIndo($indOrg->ami_rtp_tgl_pelaksanaan) 
                        : '-',
                    'normalText',
                    ['alignment' => 'center']
                );
        }

        // Footer
        $section->addTextBreak(2);
        $section->addText('Dokumen ini dibuat secara otomatis oleh sistem AMI.', 'normalText', ['alignment' => 'center', 'italic' => true]);
        $section->addText('Dicetak pada: ' . now()->format('d M Y H:i:s'), 'normalText', ['alignment' => 'center', 'italic' => true]);

        // Save and download
        $fileName = 'PTK_' . Str::slug($periode->nama_periode ?? 'AMI') . '_' . date('Ymd_His') . '.docx';
        $filePath = storage_path('app/' . $fileName);

        // Ensure directory exists
        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($filePath);

        logActivity('pemutu', "Export PTK AMI: {$fileName} oleh " . auth()->user()->name, $periode);

        return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Export Temuan Audit - Excel (KTS only)
     */
    public function exportTemuanAudit(PeriodeSpmi $periode, ?int $unitId = null, ?int $dokId = null, ?string $edStatus = null)
    {
        $filters = [
            'dok_id'          => $dokId ? encryptId($dokId) : null,
            'ed_status'       => $edStatus,
            'ami_hasil_akhir' => 0, // KTS
        ];

        $ktsIndicators = $this->indikatorService->getIndikatorOrgUnitSpmiQuery($periode, $unitId, $filters)
            ->orderBy('pemutu_indikator_orgunit.org_unit_id')
            ->orderBy('pemutu_indikator.no_indikator')
            ->get();

        if ($ktsIndicators->isEmpty()) {
            throw new \App\Exceptions\DataNotFoundException('Tidak ada indikator KTS untuk diekspor.');
        }

        return new \App\Exports\Pemutu\TemuanAuditExport($ktsIndicators, $periode);
    }

    /**
     * Export Temuan Positif - Excel (Terpenuhi & Terlampaui)
     */
    public function exportTemuanPositif(PeriodeSpmi $periode, ?int $unitId = null, ?int $dokId = null, ?string $edStatus = null)
    {
        $filters = [
            'dok_id'          => $dokId ? encryptId($dokId) : null,
            'ed_status'       => $edStatus,
            'ami_hasil_akhir' => [1, 2], // Terpenuhi & Terlampaui
        ];

        $positiveIndicators = $this->indikatorService->getIndikatorOrgUnitSpmiQuery($periode, $unitId, $filters)
            ->orderBy('pemutu_indikator_orgunit.ami_hasil_akhir', 'desc')
            ->orderBy('pemutu_indikator_orgunit.org_unit_id')
            ->orderBy('pemutu_indikator.no_indikator')
            ->get();

        if ($positiveIndicators->isEmpty()) {
            throw new \App\Exceptions\DataNotFoundException('Tidak ada temuan positif untuk diekspor.');
        }

        return new \App\Exports\Pemutu\TemuanPositifExport($positiveIndicators, $periode);
    }
}
