<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
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

        $userId = $this->route('user'); // Get the user ID from the route parameter
        $userId  = $userId ? decryptId($userId) : null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                $userId ? Rule::unique('users')->ignore($userId) : ''
            ],
            'role' => ['required', 'exists:sys_roles,name'],
            'nip' => [
                'nullable',
                'string',
                $userId ? Rule::unique('users')->ignore($userId) : ''
            ],
            'nim' => [
                'nullable',
                'string',
                $userId ? Rule::unique('users')->ignore($userId) : ''
            ],
            'password' => [
                $this->isMethod('post') ? 'required' : 'nullable', // required for create (POST), optional for update (PUT/PATCH)
                'string',
                'min:8',
                'confirmed'
            ],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // 2MB max
            'expired_at' => ['nullable', 'date'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',

            'role.required' => 'Peran wajib dipilih.',
            'role.exists' => 'Peran yang dipilih tidak valid.',

            'nip.string' => 'NIP harus berupa teks.',
            'nip.unique' => 'NIP sudah digunakan oleh pengguna lain.',

            'nim.string' => 'NIM harus berupa teks.',
            'nim.unique' => 'NIM sudah digunakan oleh pengguna lain.',

            'password.required' => 'Kata sandi wajib diisi.',
            'password.string' => 'Kata sandi harus berupa teks.',
            'password.min' => 'Kata sandi minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',

            'avatar.image' => 'File avatar harus berupa gambar.',
            'avatar.mimes' => 'File avatar harus berupa file JPEG, PNG, JPG, atau GIF.',
            'avatar.max' => 'File avatar tidak boleh lebih dari 2MB.',

            'expired_at.date' => 'Tanggal kadaluarsa tidak valid.',
        ];
    }
}
