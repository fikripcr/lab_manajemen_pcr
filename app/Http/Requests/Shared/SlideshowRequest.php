<?php
namespace App\Http\Requests\Shared;

use App\Http\Requests\BaseRequest;

class SlideshowRequest extends BaseRequest
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
            'seq'             => 'nullable|integer',
        ];
    }

    public function attributes(): array
    {
        return [
            'slideshow_image' => 'Gambar Slideshow',
            'title'           => 'Judul',
            'caption'         => 'Caption',
            'link'            => 'URL Link',
            'seq'             => 'Urutan',
        ];
    }
}
