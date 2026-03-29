<?php

namespace App\Http\Requests\Survei;

use App\Http\Requests\BaseRequest;
use App\Models\Survei\Survei;

class FormPlayerRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $slug = $this->route('slug');
        $survei = Survei::where('slug', $slug)
            ->where('is_aktif', true)
            ->firstOrFail();

        $rules = ['jawaban' => 'required|array'];
        $pertanyaanMap = $survei->pertanyaan()->pluck('tipe', 'pertanyaan_id');

        foreach ($pertanyaanMap as $id => $tipe) {
            $pertanyaan = $survei->pertanyaan()->find($id);
            if ($pertanyaan && $pertanyaan->wajib_diisi) {
                $rules["jawaban.{$id}"] = 'required';
            }
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'jawaban' => 'Jawaban',
            'jawaban.*' => 'Jawaban',
        ];
    }
}
