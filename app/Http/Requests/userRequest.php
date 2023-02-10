<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class userRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    { 
        return [
        'name'=>'required',
        'surname'=>'required',
        'telefon'=>'required',
        'email'=>'required|email',
        'password'=>'required',

        
    ];
}

public function messages()
{
    return [
        'name.required'=>'Ad daxil etmediniz!',
        'surname.required'=>'Soyad daxil etmediniz!',
        'telefon.required'=>'Telefon daxil etmediniz!',

        'email.required'=>'Email daxil etmediniz!',
        'email.email'=>'Email dogru edin!',

        'password.required'=>'Parol daxil etmediniz!',

    ];
}
}
