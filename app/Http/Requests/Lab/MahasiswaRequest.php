<?php
namespace App\Http\Requests\Lab;

use App\Http\Requests\BaseRequest;

class MahasiswaRequest extends BaseRequest
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
        $id = $this->route('mahasiswa') ? $this->route('mahasiswa')->mahasiswa_id : null;

        return [
            'nim'        => 'required|string|max:50|unique:mahasiswa,nim,' . $id . ',mahasiswa_id',
            'nama'       => 'required|string|max:255',
            'email'      => 'required|email|max:255|unique:mahasiswa,email,' . $id . ',mahasiswa_id',
            'orgunit_id' => 'required|exists:struktur_organisasi,orgunit_id',
        ];
    }
}
