<?php

namespace AccountHon\Http\Requests;

use AccountHon\Http\Requests\Request;

class AuthRequest extends Request
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
            'email' => 'required',
            'password' => 'required',
            'session'  => 'required'
        ];
    }

    public function messages()
	{
	    return [
	        'email.required' => 'El email es obligatorio.',
	        'password.required' => 'La contraseña es obligatoria.',
	        'session.required' =>  'El código de seguridad es obligatorio.'
	    ];
	}
}
