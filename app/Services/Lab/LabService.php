<?php
namespace App\Services\Lab;

use App\Models\Lab\Lab;
use Illuminate\Support\Facades\DB;

class LabService
{
    /**
     * Get filtered query for DataTables
     */
    public function getFilteredQuery(array $filters = [])
    {
        return Lab::query();
    }

    /**
     * Get Lab by ID
     */
    public function getLabById(string $id): ?Lab
    {
        return Lab::find($id);
    }

    /**
     * Create a new Lab
     */
    public function createLab(array $data): Lab
    {
        return DB::transaction(function () use ($data) {
            $lab = Lab::create($data);

            // Handle Media
            $this->handleMedia($lab, $data);

            logActivity('lab_management', "Membuat lab baru: {$lab->name}", $lab);

            return $lab;
        });
    }

    /**
     * Update an existing Lab
     */
    public function updateLab(string $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $lab     = $this->findOrFail($id);
            $oldName = $lab->name;

            $lab->update($data);

            // Handle Media
            $this->handleMedia($lab, $data);

            logActivity(
                'lab_management',
                "Memperbarui lab: {$oldName}" . ($oldName !== $lab->name ? " menjadi {$lab->name}" : ""),
                $lab
            );

            return true;
        });
    }

    /**
     * Delete a Lab
     */
    public function deleteLab(string $id): bool
    {
        return DB::transaction(function () use ($id) {
            $lab  = $this->findOrFail($id);
            $name = $lab->name;

            $lab->delete();

            logActivity('lab_management', "Menghapus lab: {$name}");

            return true;
        });
    }

    /**
     * Handle Media Uploads (Images and Attachments)
     */
    protected function handleMedia(Lab $lab, array $data)
    {
        // Handle images
        if (isset($data['lab_images']) && is_array($data['lab_images'])) {
            foreach ($data['lab_images'] as $image) {
                if ($image->isValid()) {
                    $lab->addMedia($image)
                        ->withCustomProperties(['uploaded_by' => auth()->id()])
                        ->toMediaCollection('lab_images');
                }
            }
        }

        // Handle attachments
        if (isset($data['lab_attachments']) && is_array($data['lab_attachments'])) {
            foreach ($data['lab_attachments'] as $attachment) {
                if ($attachment->isValid()) {
                    $lab->addMedia($attachment)
                        ->withCustomProperties(['uploaded_by' => auth()->id()])
                        ->toMediaCollection('lab_attachments');
                }
            }
        }
    }

    protected function findOrFail(string $id): Lab
    {
        $model = Lab::find($id);
        if (! $model) {
            throw new \Exception("Lab dengan ID {$id} tidak ditemukan.");
        }
        return $model;
    }
}
