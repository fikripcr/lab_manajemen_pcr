<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class JenisIndisiplinerRequest extends BaseRequest
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
        $uniqueRule = 'unique:hr_jenis_indisipliner,jenis_indisipliner';

        // On update, exclude current record from unique check
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $jenisIndisipliner  = $this->route('jenis_indisipliner');
            $uniqueRule        .= ',' . $jenisIndisipliner->jenisindisipliner_id . ',jenisindisipliner_id';
        }

        return [
            'jenis_indisipliner' => 'required|string|max:100|' . $uniqueRule,
        ];
    }

    public function attributes(): array
    {
        return [
            'jenis_indisipliner' => 'Jenis Indisipliner',
        ];
    }
}
