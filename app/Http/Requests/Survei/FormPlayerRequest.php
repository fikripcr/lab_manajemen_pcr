<?php
namespace App\Http\Requests\Survei;

use App\Models\Survei\Survei;
use Illuminate\Foundation\Http\FormRequest;

class FormPlayerRequest extends FormRequest
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
        $slug   = $this->route('slug');
        $survei = Survei::where('slug', $slug)
            ->where('is_aktif', true)
            ->firstOrFail();

        $rules         = ['jawaban' => 'required|array'];
        $pertanyaanMap = $survei->pertanyaan()->pluck('tipe', 'pertanyaan_id');

        foreach ($pertanyaanMap as $id => $tipe) {
            $pertanyaan = $survei->pertanyaan()->find($id);
            if ($pertanyaan && $pertanyaan->wajib_diisi) {
                $rules["jawaban.{$id}"] = 'required';
            }
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'jawaban.required'   => 'Anda harus mengisi minimal satu jawaban.',
            'jawaban.*.required' => 'Pertanyaan wajib harus diisi.',
        ];
    }
}
