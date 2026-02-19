<?php
namespace App\Http\Requests\Shared;

use Illuminate\Foundation\Http\FormRequest;

class PublicMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parent_id' => 'nullable|exists:public_menus,menu_id',
            'title'     => 'required|string|max:255',
            'url'       => 'nullable|string|max:255',
            'type'      => 'required|in:url,page,route',
            'page_id'   => 'nullable|exists:public_pages,page_id',
            'position'  => 'required|string|max:50',
            'target'    => 'required|in:_self,_blank',
            'is_active' => 'boolean',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'is_active' => $this->has('is_active'),
        ]);

        if ($this->has('parent_id') && $this->parent_id) {
            $decoded = \Hashids::decode($this->parent_id);
            if (! empty($decoded)) {
                $this->merge(['parent_id' => $decoded[0]]);
            }
        }

        if ($this->has('page_id') && $this->page_id) {
            $decoded = \Hashids::decode($this->page_id);
            if (! empty($decoded)) {
                $this->merge(['page_id' => $decoded[0]]);
            }
        }
    }
}
