<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class DokumenApprovalRequest extends BaseRequest
{
    /**
     */

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        if ($this->has('approvers') && is_array($this->approvers)) {
            $approvers = $this->approvers;
            foreach ($approvers as $key => $approver) {
                if (isset($approver['pegawai_id'])) {
                    try {
                        $approvers[$key]['pegawai_id'] = decryptIdIfEncrypted($approver['pegawai_id']);
                    } catch (\Exception $e) {
                        // Keep original value if decryption fails
                    }
                }
            }
            $this->merge(['approvers' => $approvers]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'approvers'              => 'required|array|min:1',
            'approvers.*.pegawai_id' => 'required|exists:hr_pegawai,pegawai_id',
            'approvers.*.jabatan'    => 'required|string|max:191',
        ];
    }

    public function attributes(): array
    {
        return [
            'approvers'              => 'Daftar Approver',
            'approvers.*.pegawai_id' => 'Pegawai Approver',
            'approvers.*.jabatan'    => 'Jabatan Approver',
        ];
    }
}
