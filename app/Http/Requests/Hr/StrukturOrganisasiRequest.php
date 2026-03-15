<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class StrukturOrganisasiRequest extends BaseRequest
{
    /**
     */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'parent_id'   => [
                'nullable',
                'exists:struktur_organisasi,orgunit_id',
                function ($attribute, $value, $fail) {
                    $orgUnit = $this->route('hr_struktur_organisasi');
                    if ($orgUnit && $value == $orgUnit->orgunit_id) {
                        $fail('Unit tidak bisa menjadi induk bagi dirinya sendiri.');
                        return;
                    }

                    if ($orgUnit) {
                        // Check if the selected parent is a descendant of the current unit
                        $isDescendant = $this->checkIsDescendant($orgUnit->orgunit_id, $value);
                        if ($isDescendant) {
                            $fail('Unit yang dipilih sebagai induk adalah bagian dari bawahan unit ini (akan menyebabkan perulangan).');
                        }
                    }
                },
            ],
            'name'        => 'required|string|max:191',
            'code'        => 'nullable|string|max:50',
            'type'        => 'required|string',
            'description' => 'nullable|string',
            'is_active'   => 'sometimes|boolean',
            'seq'         => 'nullable|integer',
        ];
    }

    /**
     * Check if potentialParentId is a descendant of unitId
     */
    protected function checkIsDescendant($unitId, $potentialParentId)
    {
        $children = \DB::table('hr_struktur_organisasi')->where('parent_id', $unitId)->pluck('orgunit_id');
        foreach ($children as $childId) {
            if ($childId == $potentialParentId) {
                return true;
            }
            if ($this->checkIsDescendant($childId, $potentialParentId)) {
                return true;
            }
        }
        return false;
    }

    public function attributes(): array
    {
        return [
            'parent_id'   => 'Induk Organisasi',
            'name'        => 'Nama Unit',
            'code'        => 'Kode Unit',
            'type'        => 'Tipe',
            'description' => 'Deskripsi',
            'is_active'   => 'Status Aktif',
            'color'       => 'Warna Label',
            'sort_order'  => 'Urutan Sortir',
            'seq'         => 'Urutan',
        ];
    }

    public function prepareForValidation()
    {
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => $this->boolean('is_active'),
            ]);
        }

        if ($this->filled('parent_id')) {
            $this->merge([
                'parent_id' => decryptId($this->parent_id),
            ]);
        }
    }
}
