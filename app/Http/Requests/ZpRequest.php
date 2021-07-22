<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ZpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'oklad' => 'required|int',
            'normForMonth' => 'required|int|max:31',
            'workedDays' => 'required|int|max:31',
            'hasNalog' => 'required|bool',
            'year' => 'required|int|max:2021',
            'month' => 'required|int|max:12|min:1',
            'isPensioner' => 'required|bool',
            'isInvalid' => 'required|bool',
            'invalidGroup' => 'int|required_if:isInvalid,==,1'
        ];
    }

}
