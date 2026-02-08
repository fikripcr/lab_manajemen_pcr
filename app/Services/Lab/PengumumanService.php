<?php
namespace App\Services\Lab;

use App\Models\Lab\Pengumuman;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengumumanService
{
    /**
     * Get Query for DataTables
     */
    public function getFilteredQuery(string $type)
    {
        return Pengumuman::with(['penulis', 'media'])
            ->where('jenis', $type);
    }

    /**
     * Get Pengumuman by ID
     */
    public function getPengumumanById(string $id): ?Pengumuman
    {
        return Pengumuman::find($id);
    }

    /**
     * Create a new Pengumuman
     */
    public function createPengumuman(array $data): Pengumuman
    {
        return DB::transaction(function () use ($data) {
            $isPublished = $data['is_published'] ?? false;

            $pengumuman = Pengumuman::create([
                'judul'        => $data['judul'],
                'isi'          => $data['isi'],
                'jenis'        => $data['jenis'],
                'penulis_id'   => Auth::id(),
                'is_published' => $isPublished,
                'published_at' => $isPublished ? now() : null,
            ]);

            // Handle Media
            $this->handleMedia($pengumuman, $data);

            logActivity(
                'pengumuman_management',
                "Membuat {$data['jenis']} baru: {$pengumuman->judul}"
            );

            return $pengumuman;
        });
    }

    /**
     * Update an existing Pengumuman
     */
    public function updatePengumuman(string $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $pengumuman = $this->findOrFail($id);
            $oldTitle   = $pengumuman->judul;

            $isPublished = $data['is_published'] ?? false;

            $pengumuman->update([
                'judul'        => $data['judul'],
                'isi'          => $data['isi'],
                'is_published' => $isPublished,
                // Update published_at only if status changes to published?
                // Or if it was drafted? Existing logic: if published now, set now.
                // If it was already published, should we keep original date?
                // Existing controller: if published ? now() : $pengumuman->published_at.
                // This means every update to a published item updates the date to NOW.
                // Maybe better: use $data['published_at'] if provided, or verify logic.
                // Controller logic was: 'published_at' => $isPublished ? now() : $pengumuman->published_at
                // Wait, if $isPublished is true, it sets now(). If false, it keeps old date (which might be null or old date).
                // If I unpublish, it keeps old date? That seems okay.
                // If I republish, it updates to now.
                'published_at' => $isPublished ? now() : $pengumuman->published_at,
            ]);

            // Handle Media
            $this->handleMedia($pengumuman, $data);

            logActivity(
                'pengumuman_management',
                "Memperbarui {$pengumuman->jenis}: {$oldTitle}" . ($oldTitle !== $pengumuman->judul ? " menjadi {$pengumuman->judul}" : "")
            );

            return true;
        });
    }

    /**
     * Delete a Pengumuman
     */
    public function deletePengumuman(string $id): string
    {
        return DB::transaction(function () use ($id) {
            $pengumuman = $this->findOrFail($id);
            $jenis      = $pengumuman->jenis;
            $judul      = $pengumuman->judul;

            $pengumuman->delete();

            logActivity('pengumuman_management', "Menghapus {$jenis}: {$judul}");

            return $jenis;
        });
    }

    /**
     * Handle Media Uploads
     */
    protected function handleMedia(Pengumuman $pengumuman, array $data)
    {
        // Handle Cover
        if (isset($data['cover']) && $data['cover']) {
            $pengumuman->clearMediaCollection('cover');
            $pengumuman->addMedia($data['cover'])->toMediaCollection('cover');
        }

        // Handle Attachments
        if (isset($data['attachments']) && is_array($data['attachments'])) {
            // Existing logic cleared attachments on update if new ones provided.
            // "if ($request->hasFile('attachments')) { ... clear ... foreach ... }"
            // Create logic just added.
            // Let's mimic Controller: if 'attachments' key exists in data (even if empty? no, usually check hasFile), clear and add.
            // But here $data might contain other things.
            // Controller: if ($request->hasFile('attachments'))

            // If creation, no need to clear.
            // If update, only clear if new files provided?
            // "if ($request->hasFile('attachments')) { $pengumuman->clearMediaCollection('attachments'); ... }"
            // My Service Logic:

            // We assume if 'attachments' is passed, it means we want to replace/add.
            // To support "adding" vs "replacing", we need to know intent.
            // Controller logic was "Replace All" on Update if files provided.

            // Allow checking if we are updating or creating is hard inside here unless we check model existence/wasRecentlyCreated.
            // But $pengumuman is passed.

            if ($pengumuman->exists && count($data['attachments']) > 0) {
                // Strategy: Clear only if updating?
                // Controller did: $pengumuman->clearMediaCollection('attachments');
                $pengumuman->clearMediaCollection('attachments');
            }

            foreach ($data['attachments'] as $file) {
                $pengumuman->addMedia($file)->toMediaCollection('attachments');
            }
        }
    }

    protected function findOrFail(string $id): Pengumuman
    {
        $model = Pengumuman::find($id);
        if (! $model) {
            throw new \Exception("Data tidak ditemukan.");
        }
        return $model;
    }
}
