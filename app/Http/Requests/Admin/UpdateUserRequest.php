<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class UpdateUserRequest extends Request {

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
			'email' => ['required', 'email', 'unique:user,email,' . $this->route()->parameter('user')->id],
			'phone' => ['required'],
			'title' => ['required'],
			'function' => ['required'],
			'name' => ['required'],
			'family_name' => ['required'],
			'password' => ['sometimes', 'min:8'],
		];
	}

	public function messages()
	{
		return [
			'email.required' => 'Veuillez renseigner l\'adresse email.',
			'email.email' => 'Votre adresse email n\'est pas au bon format.',
			'email.unique' => 'L\'adresse email doit être unique.',
			'phone.required' => 'Veuillez renseigner le numéro de téléphone.',
			'title.required' => 'Veuillez renseigner la civilité.',
			'function.required' => 'Veuillez renseigner la fonction.',
			'name.required' => 'Veuillez renseigner le nom.',
			'family_name.required' => 'Veuillez renseigner le prénom.',
			'password.min' => 'Votre mot de passe doit faire plus de 8 caractères.',
		];
	}

}
