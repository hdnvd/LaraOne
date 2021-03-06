<?php

namespace App\Http\Requests\placeman;

use App\Http\Requests\sweetRequest;

class placeman_cityUpdateRequest extends sweetRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $Fields = [

            'title' => 'required',
            'province' => 'required|min:-1|integer',
        ];
        return $Fields;
    }

    public function messages()
    {
        return [

            'title.required' => 'وارد کردن عنوان اجباری می باشد',
            'province.required' => 'وارد کردن استان اجباری می باشد',
            'province.integer' => 'مقدار استان صحیح وارد نشده است.',
        ];
    }
}