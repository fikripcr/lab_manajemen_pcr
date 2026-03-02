<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class EndStrukturalRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'tgl_akhir' => 'required|date',
        ];
    }

    public function attributes(): array
    {
        return [
            'tgl_akhir' => 'Tanggal Akhir',
        ];
    }
}
