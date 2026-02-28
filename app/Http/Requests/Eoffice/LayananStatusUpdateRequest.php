<?php
namespace App\Http\Requests\Eoffice;

use App\Http\Requests\BaseRequest;

class LayananStatusUpdateRequest extends BaseRequest
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
        // If status is present in route/request, we might need conditional logic here or in controller.
        // Controller logic was: if (! in_array($status, ['proses', 'batal'])) validate...
        // FormRequest doesn't easily know about route params unless we ask.
        // $status = $this->route('status');

        return [
            'status_layanan' => 'required_unless:status,proses,batal|string',
            'keterangan'     => 'nullable|string',
            'disposisi_seq'  => 'nullable|integer',
            'file_lampiran'  => 'nullable|file|mimes:pdf,docx,zip,jpg,png|max:5120',
            // 'status' param is from route, so we can use required_unless if we merge route params or check logic.
            // A simpler approach for now is to replicate the controller logic:
            // The controller passed $status.
            // We can check $this->route('status').
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge(['status' => $this->route('status')]);
    }
}
