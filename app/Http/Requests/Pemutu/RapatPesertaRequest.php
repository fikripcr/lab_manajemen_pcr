<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class RapatPesertaRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rapat_id' => 'required|exists:rapats,rapat_id',
            'jabatan'  => 'required|string|max:100',
        ];
    }

    public function attributes(): array
    {
        return [
            'rapat_id' => 'Rapat',
            'user_id'  => 'Peserta Rapat',
            'jabatan'  => 'Jabatan',
        ];
    }
}
