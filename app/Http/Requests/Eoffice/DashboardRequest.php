<?php
namespace App\Http\Requests\Eoffice;

use Illuminate\Foundation\Http\FormRequest;

class DashboardRequest extends FormRequest
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
