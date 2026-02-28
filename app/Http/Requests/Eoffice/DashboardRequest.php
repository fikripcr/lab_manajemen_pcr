<?php
namespace App\Http\Requests\Eoffice;

use App\Http\Requests\BaseRequest;

class DashboardRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'period' => 'nullable|string|in:today,week,month,year',
        ];
    }
}
