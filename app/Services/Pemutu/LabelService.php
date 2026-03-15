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
        $query = Label::with('parent')->select('*');

        if (! empty($filters['parent_id'])) {
            $parentId = decryptIdIfEncrypted($filters['parent_id']);
            $query->where('parent_id', $parentId);
        } else {
            // default mode without parent_id filter could show all or only parents. Let's show all if no parent_id selected.
        }

        return $query;
    }

    public function getTotalLabels(): int
    {
        return Label::count();
    }

    public function getLabelById(int $id): ?Label
    {
        return Label::with('parent')->find($id);
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

    // --- PARENT LABEL Logic ---

    public function getParentLabels()
    {
        return Label::with(['children' => function($q) {
            $q->orderBy('name');
        }])->whereNull('parent_id')->orderBy('name')->get();
    }
}
