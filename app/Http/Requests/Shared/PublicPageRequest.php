<?php
namespace App\Http\Requests\Shared;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PublicPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Get the page ID from the route if it exists (for update uniqueness)
        // Assuming route parameter is 'public_page' and it's bound via HashidBinding
        $page   = $this->route('public_page');
        $pageId = $page ? $page->page_id : null;

        return [
            'title'         => 'required|string|max:255',
            'slug'          => ['nullable', 'string', 'max:255', Rule::unique('public_pages', 'slug')->ignore($pageId, 'page_id')],
            'content'       => 'nullable|string',
            'meta_desc'     => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'is_published'  => 'boolean',
            'main_image'    => 'nullable|file|mimes:jpeg,png,jpg,webp|max:5120', // 5MB
            'attachments.*' => 'nullable|file|max:10240',                        // 10MB
        ];
    }

    public function messages(): array
    {
        return [
            'main_image.image'       => 'File harus berupa gambar.',
            'main_image.mimes'       => 'Format gambar harus :values.',
            'main_image.max'         => 'Ukuran gambar maksimal 5MB.',
            'main_image.uploaded'    => 'Gagal mengupload gambar utama. Pastikan ukuran tidak melebihi batas server.',
            'attachments.*.max'      => 'Ukuran file maksimal 10MB.',
            'attachments.*.uploaded' => 'Gagal mengupload file pendukung.',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'is_published' => $this->has('is_published'),
        ]);
    }
}
