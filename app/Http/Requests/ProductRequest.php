<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
 
              'name' => 'required',
			  'price' => 'required|max:7'
            //'price' => 'required|min:1'
 
        ];
    }
     
	 /* Get the error messages for the defined validation rules.
     * @return array
     */
    public function messages() //перевод ошибок
    {
        return [
		    'price.max' => 'В поле наименование необходимо не более 7 цыфр!',
            //'price.min' => 'В поле наименование необходимо минимум 1 цыфру!',
            'name.required'  => 'Поле наимен. не должно быть пустым!',
			'price.required'  => 'Поле цена не должно быть пустым!',

        ];
    }
}
