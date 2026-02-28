<?php
namespace App\Http\Requests\Lab;

use Illuminate\Validation\Rule;
use App\Http\Requests\BaseRequest;

class UserRequest extends BaseRequest
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
        $userId = $userId ? decryptId($userId) : null;

        return [
            'name'       => ['required', 'string', 'max:255'],
            'email'      => [
                'required',
                'email',
                $userId ? Rule::unique('users')->ignore($userId) : '',
            ],
            'role'       => ['required', 'array'],
            'role.*'     => ['exists:sys_roles,name'],
            'password'   => [
                $this->isMethod('post') ? 'required' : 'nullable', // required for create (POST), optional for update (PUT/PATCH)
                'string',
                'min:8',
                'confirmed',
            ],
            'avatar'     => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // 2MB max
            'expired_at' => ['nullable', 'date'],
        ];
    }

}
