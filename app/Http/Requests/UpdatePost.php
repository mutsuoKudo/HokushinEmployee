<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePost extends FormRequest
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
            // 'shain_cd' => 'required|unique:employees',
            'shain_cd' => 'required',
            'shain_mei' => 'required',
            'shain_mei_kana' => 'required',
            'shain_mei_romaji' => 'required',
            'gender' => 'required',
            //
        ];
    }

    public function messages()
    {
        return [
            'shain_cd.required' => '必須項目です。',
            'shain_mei.required' => '必須項目です。',
            'shain_mei_kana.required' => '必須項目です。',
            'shain_mei_romaji.required' => '必須項目です。',
            'gender.required' => '必須項目です。',
            // 'shain_cd.unique' => 'すでに使用されています。',
        ];
    }
}
