<?php
namespace App\Services\Pemtu;

use App\Models\Pemtu\DokSub;
use App\Models\Pemtu\Indikator;

class IndikatorService
{
    /**
     * Generate Auto Increment No Indikator
     * Format: YYMM[001] based on Dokumen Period
     */
    public function generateNoIndikator(DokSub $dokSub)
    {
        $year  = now()->year;
        $month = now()->format('m');

        if ($dokSub->dokumen && $dokSub->dokumen->periode) {
            $rawPeriode = trim($dokSub->dokumen->periode);
            if (preg_match('/^(\d{4})/', $rawPeriode, $matches)) {
                $year = intval($matches[1]);
            } else {
                $year = intval($rawPeriode) ?: now()->year;
            }
        }

                                         // Month defaults to 01 if not derived from something specific,
                                         // OR user meant current month of creation?
                                         // "YYMM001" usually implies creation date or period date.
                                         // If period is just Year (2024), what is MM?
                                         // Using current month of creation seems appropriate for "YYMM".
                                         // Let's use Creation Date (Now).
        $yearCode  = substr($year, -2);  // YY
        $monthCode = now()->format('m'); // MM

        $prefix = $yearCode . $monthCode;

        // Find last no_indikator starting with prefix
        $lastIndikator = Indikator::where('no_indikator', 'like', "$prefix%")->orderBy('no_indikator', 'desc')->first();

        if ($lastIndikator) {
            // Extract last 3 digits
            $lastNo = intval(substr($lastIndikator->no_indikator, strlen($prefix)));
            $nextNo = $lastNo + 1;
        } else {
            $nextNo = 1;
        }

        return $prefix . str_pad($nextNo, 3, '0', STR_PAD_LEFT);
    }

    public function generateSeq($doksubId)
    {
        $maxSeq = Indikator::where('doksub_id', $doksubId)->max('seq');
        return $maxSeq ? $maxSeq + 1 : 1;
    }
}
