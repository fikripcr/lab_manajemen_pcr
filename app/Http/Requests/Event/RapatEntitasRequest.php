<?php
namespace App\Http\Requests\Event;

use App\Http\Requests\BaseRequest;

class RapatEntitasRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'rapat_id'   => 'required|exists:event_rapat,rapat_id',
            'model'      => 'required|string|max:50',
            'model_id'   => 'required|integer',
            'keterangan' => 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('rapat_id')) {
            $this->merge([
                'rapat_id' => decryptIdIfEncrypted($this->rapat_id),
            ]);
        }

        if ($this->has('entity') && ! empty($this->entity)) {
            $parts = explode(':', $this->entity);
            if (count($parts) === 2) {
                $modelShortName = $parts[0];
                $modelId        = $parts[1];

                // Mapping short name to full class name
                $modelMap = [
                    'IndikatorOrgUnit'   => \App\Models\Pemutu\IndikatorOrgUnit::class,
                    'StrukturOrganisasi' => \App\Models\Hr\StrukturOrganisasi::class,
                    'Indikator'          => \App\Models\Pemutu\Indikator::class,
                ];

                $modelClass = $modelMap[$modelShortName] ?? $modelShortName;

                $this->merge([
                    'model'    => $modelClass,
                    'model_id' => $modelId,
                ]);
            }
        }
    }

    public function attributes(): array
    {
        return [
            'rapat_id'   => 'Rapat',
            'model'      => 'Entitas',
            'model_id'   => 'ID Entitas',
            'keterangan' => 'Keterangan',
        ];
    }
}
