<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
 
              'date' => 'required',
			  'phone' => 'required',
			 // 'phone' => 'required|min:11',
              'email' => 'required',
			  'addres' => 'required',
			 // 'total' => 'required|min:3000', 
			 'total' => 'required', 
			 'tovar' => 'required',
        ];
    }
	
	public function messages() //перевод ошибок
    {
        return [
		    'date.required'  => 'Поле даты не должно быть пустым!', 
			'phone.required' => 'Поле телефон не должно быть пустым!',
		   // 'phone.max' => 'В поле телефон необходимо не более 12 символов!',
           // 'phone.min' => 'В поле телефон необходимо минимум 11 цыфр!',            
			 'email.required'  => 'Поле email. не должно быть пустым!',
			 'addres.required'  => 'Поле адрес. не должно быть пустым!',
			//'total.min'  => 'Сумма заказа не должна быть менее 3000!', 
			'total.required'  => 'Сумма заказа не должна быть менее 3000!',
			'tovar.required'  => 'Проверте добавлен ли товар в заказ!',
        ];
    }
	
/* 	public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            request()->merge([
                'new_err' => $this->request->get('какое-то значение');
            ]);

            $validator->errors()->add('new_err', 'Вот ещё что то не верно заполнено!');
        });
    } */
}
