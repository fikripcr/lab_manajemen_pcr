<?php
namespace App\Services\Pemutu;

use App\Models\Pemutu\Label;
use App\Models\Pemutu\LabelType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LabelService
{
    // --- LABEL Logic ---

    public function getLabelFilteredQuery(array $filters = [])
    {
        $query = Label::with('type')->select('label.*');

        if (! empty($filters['type_id'])) {
            $query->where('type_id', $filters['type_id']);
        }

        // Add more filters if needed

        return $query;
    }

    public function getLabelById(int $id): ?Label
    {
        return Label::with('type')->find($id);
    }

    public function createLabel(array $data): Label
    {
        return DB::transaction(function () use ($data) {
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }

            $label = Label::create($data);

            logActivity(
                'label_management',
                "Membuat label baru: {$label->name}"
            );

            return $label;
        });
    }

    public function updateLabel(int $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $label   = $this->findLabelOrFail($id);
            $oldName = $label->name;

            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }

            $label->update($data);

            logActivity(
                'label_management',
                "Memperbarui label: {$oldName}" . ($oldName !== $label->name ? " menjadi {$label->name}" : "")
            );

            return true;
        });
    }

    public function deleteLabel(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $label = $this->findLabelOrFail($id);
            $name  = $label->name;

            $label->delete();

            logActivity(
                'label_management',
                "Menghapus label: {$name}"
            );

            return true;
        });
    }

    protected function findLabelOrFail(int $id): Label
    {
        $model = Label::find($id);
        if (! $model) {
            throw new \Exception("Label dengan ID {$id} tidak ditemukan.");
        }
        return $model;
    }

    // --- LABEL TYPE Logic ---

    public function getAllLabelTypes()
    {
        return LabelType::orderBy('name')->get();
    }

    public function getLabelTypeById(int $id): ?LabelType
    {
        return LabelType::find($id);
    }

    public function createLabelType(array $data): LabelType
    {
        return DB::transaction(function () use ($data) {
            $type = LabelType::create($data);

            logActivity(
                'label_type_management',
                "Membuat tipe label baru: {$type->name}"
            );

            return $type;
        });
    }

    public function updateLabelType(int $id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $type    = $this->findLabelTypeOrFail($id);
            $oldName = $type->name;

            $type->update($data);

            logActivity(
                'label_type_management',
                "Memperbarui tipe label: {$oldName}" . ($oldName !== $type->name ? " menjadi {$type->name}" : "")
            );

            return true;
        });
    }

    public function deleteLabelType(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $type = $this->findLabelTypeOrFail($id);
            $name = $type->name;

            // Check if used by labels?
            if ($type->labels()->count() > 0) {
                // Throw error or handle?
                // Usually restriction.
                throw new \Exception("Tipe Label '{$name}' tidak bisa dihapus karena masih digunakan oleh label.");
            }

            $type->delete();

            logActivity(
                'label_type_management',
                "Menghapus tipe label: {$name}"
            );

            return true;
        });
    }

    protected function findLabelTypeOrFail(int $id): LabelType
    {
        $model = LabelType::find($id);
        if (! $model) {
            throw new \Exception("Tipe Label dengan ID {$id} tidak ditemukan.");
        }
        return $model;
    }
}
