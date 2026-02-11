<?php
namespace App\Services\Eoffice;

use App\Models\Eoffice\JenisLayananDisposisi;

class JenisLayananDisposisiService
{
    /**
     * Get all disposisi for a given Jenis Layanan (ordered by seq).
     */
    public function getByJenisLayanan($jenislayananId)
    {
        return JenisLayananDisposisi::where('jenislayanan_id', $jenislayananId)
            ->orderBy('seq')
            ->get();
    }

    /**
     * Store a new disposisi with auto-incremented sequence.
     */
    public function store($jenislayananId, array $data)
    {
        // Auto-increment seq
        $latestSeq = JenisLayananDisposisi::where('jenislayanan_id', $jenislayananId)
            ->max('seq') ?? 0;

        $data['seq']             = $latestSeq + 1;
        $data['jenislayanan_id'] = $jenislayananId;
        $data['is_notify_email'] = $data['is_notify_email'] ?? true;

        return JenisLayananDisposisi::create($data);
    }

    /**
     * Update the sequence of a disposisi item.
     */
    public function updateSeq($id, $newSeq)
    {
        $item = JenisLayananDisposisi::findOrFail($id);
        $item->update(['seq' => $newSeq]);
        return $item;
    }

    /**
     * Toggle email notification flag.
     */
    public function updateNotifyEmail($id, $isNotify)
    {
        $item = JenisLayananDisposisi::findOrFail($id);
        $item->update(['is_notify_email' => $isNotify]);
        return $item;
    }

    /**
     * Update text, keterangan, and batas_pengerjaan fields.
     */
    public function updateTextKet($id, array $data)
    {
        $item = JenisLayananDisposisi::findOrFail($id);
        $item->update([
            'text'             => $data['text'] ?? $item->text,
            'keterangan'       => $data['keterangan'] ?? $item->keterangan,
            'batas_pengerjaan' => $data['batas_pengerjaan'] ?? $item->batas_pengerjaan,
        ]);
        return $item;
    }

    /**
     * Delete a disposisi and re-sequence the remaining items.
     */
    public function destroy($id)
    {
        $item           = JenisLayananDisposisi::findOrFail($id);
        $jenislayananId = $item->jenislayanan_id;
        $item->delete();

        // Re-sequence remaining items
        $remaining = JenisLayananDisposisi::where('jenislayanan_id', $jenislayananId)
            ->orderBy('seq')
            ->get();

        $seq = 1;
        foreach ($remaining as $d) {
            $d->update(['seq' => $seq++]);
        }

        return true;
    }

    /**
     * Get single disposisi by ID.
     */
    public function getById($id)
    {
        return JenisLayananDisposisi::findOrFail($id);
    }
}
