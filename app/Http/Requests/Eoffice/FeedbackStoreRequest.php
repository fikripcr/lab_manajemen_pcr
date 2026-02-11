<?php

namespace App\Http\Requests\Eoffice;

use Illuminate\Foundation\Http\FormRequest;

class FeedbackStoreRequest extends FormRequest
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
            'layanan_id' => 'required',
            'rating' => 'required|numeric|min:1|max:5',
            'feedback' => 'required|string',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'layanan_id.required' => 'Layanan harus dipilih.',
            'rating.required' => 'Silahkan mengisi rating bintang.',
            'rating.numeric' => 'Rating harus berupa angka.',
            'rating.min' => 'Rating minimal 1.',
            'rating.max' => 'Rating maksimal 5.',
            'feedback.required' => 'Silahkan mengisi kolom komentar.',
            'feedback.string' => 'Feedback harus berupa string.',
        ];
    }
}
