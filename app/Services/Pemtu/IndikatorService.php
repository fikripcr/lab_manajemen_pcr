<?php
namespace App\Services\Pemtu;

use App\Models\Pemtu\DokSub;
use App\Models\Pemtu\Indikator;
use Illuminate\Support\Facades\DB;

class IndikatorService
{
    public function getFilteredQuery(array $filters = [])
    {
        $query = Indikator::query()->with(['dokSub.dokumen', 'labels.type']);

        // Add filters logic if needed
        return $query;
    }

    public function getIndikatorById(int $id): ?Indikator
    {
        return Indikator::with(['labels', 'orgUnits', 'relatedDokSubs', 'dokSub.dokumen'])->find($id);
    }

    public function createIndikator(array $data): Indikator
    {
        return DB::transaction(function () use ($data) {
            // Generate No & Seq if not provided
            // Controller did this before. But service should handle business logic.
            // If we pass doksub_id, we can generate it here.

            if (empty($data['no_indikator']) && ! empty($data['doksub_id'])) {
                $dokSub = DokSub::with('dokumen')->find($data['doksub_id']);
                if ($dokSub) {
                    $data['no_indikator'] = $this->generateNoIndikator($dokSub);
                }
            }

            if (empty($data['seq']) && ! empty($data['doksub_id'])) {
                $data['seq'] = $this->generateSeq($data['doksub_id']);
            }

            $indikator = Indikator::create($data);

            // Sync Relations
            if (isset($data['labels'])) {
                $indikator->labels()->sync($data['labels']);
            }

            if (isset($data['org_units'])) {
                // Format: [id => ['target' => val], ...] or handled outside?
                // Controller had logic to parse 'assignments' array.
                // Service should expect clean data or handle raw data?
                // Let's expect the Controller to prepare the sync array OR handle raw if consistent.
                // Controller logic was: parse 'assignments' -> syncData.
                // Better to pass 'org_units' as formatted sync array to service?
                // Or keep parsing logic in controller?
                // Service should be reusable. Passing pre-formatted sync array is cleaner.
                // But let's check $data['org_units'].
                // If the logic is "sync this array", just sync.
                $indikator->orgUnits()->sync($data['org_units']);
            }

            if (isset($data['related_doksubs'])) {
                $indikator->relatedDokSubs()->sync($data['related_doksubs']);
            }

            logActivity(
                'indikator_management',
                "Membuat indikator baru: {$indikator->indikator}"
            );

            return $indikator;
        });
    }

    public function updateIndikator(int $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $indikator = $this->findOrFail($id);
            $oldName   = $indikator->indikator;

            $indikator->update($data);

            // Sync Relations
            if (isset($data['labels'])) {
                $indikator->labels()->sync($data['labels']);
            } elseif (array_key_exists('labels', $data)) { // If key exists but null/empty
                $indikator->labels()->detach();
            }

            if (isset($data['org_units'])) {
                $indikator->orgUnits()->sync($data['org_units']);
            } elseif (array_key_exists('org_units', $data)) {
                $indikator->orgUnits()->detach();
            }

            if (isset($data['related_doksubs'])) {
                $indikator->relatedDokSubs()->sync($data['related_doksubs']);
            } elseif (array_key_exists('related_doksubs', $data)) {
                $indikator->relatedDokSubs()->detach();
            }

            logActivity(
                'indikator_management',
                "Memperbarui indikator: " . $indikator->no_indikator
            );

            return true;
        });
    }

    public function deleteIndikator(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $indikator = $this->findOrFail($id);
            $no        = $indikator->no_indikator;

            $indikator->delete(); // Pivots cascade? Explicit detach?
                                  // Indikator Model has belongsToMany.
                                  // Usually need to detach if Foreign Keys don't cascade delete.
                                  // Let's assume DB level cascade or Laravel detach?
                                  // Laravel doesn't auto-detach on delete unless using 'deleting' event.
                                  // Safe to detach manually or trust DB.
            $indikator->labels()->detach();
            $indikator->orgUnits()->detach();
            $indikator->relatedDokSubs()->detach();

            logActivity(
                'indikator_management',
                "Menghapus indikator: {$no}"
            );

            return true;
        });
    }

    protected function findOrFail(int $id): Indikator
    {
        $model = Indikator::find($id);
        if (! $model) {
            throw new \Exception("Indikator dengan ID {$id} tidak ditemukan.");
        }
        return $model;
    }

    /**
     * Generate Auto Increment No Indikator
     */
    public function generateNoIndikator(DokSub $dokSub)
    {
        $year  = now()->year;
        $month = now()->format('m'); // Default current month

        if ($dokSub->dokumen && $dokSub->dokumen->periode) {
            $rawPeriode = trim($dokSub->dokumen->periode);
            if (preg_match('/^(\d{4})/', $rawPeriode, $matches)) {
                $year = intval($matches[1]);
            } else {
                $year = intval($rawPeriode) ?: now()->year;
            }
        }

        // Logic: YYMM + 001
        // YY from Period Year
        // MM from Current Month?
        // If creating historical indicator, maybe obscure.
        // Let's stick to existing logic:
        $yearCode  = substr($year, -2);
        $monthCode = now()->format('m');

        $prefix = $yearCode . $monthCode;

        $lastIndikator = Indikator::where('no_indikator', 'like', "$prefix%")->orderBy('no_indikator', 'desc')->first();

        if ($lastIndikator) {
            // Extract last 3 digits
            // Warning: prefix length is 4. substring($len) gets rest.
            // Assuming format matches.
            $suffixStr = substr($lastIndikator->no_indikator, strlen($prefix));
            // Ensure numeric
            $lastNo = intval($suffixStr);
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
