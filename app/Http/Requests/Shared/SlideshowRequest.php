<?php
namespace App\Http\Requests\Shared;

use Illuminate\Foundation\Http\FormRequest;

class SlideshowRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'slideshow_image' => ($this->isMethod('POST') ? 'required' : 'nullable') . '|image|mimes:jpeg,png,jpg,gif',
            'title'           => 'nullable|string|max:191',
            'caption'         => 'nullable|string',
            'link'            => 'nullable|url|max:191',
            'seq'             => 'nullable|integer',
        ];
    }
}
