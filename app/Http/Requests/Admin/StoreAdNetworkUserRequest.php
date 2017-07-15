<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class StoreAdNetworkUserRequest extends Request {

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
			'email' => ['required', 'email', 'unique:ad_network_user,email'],
			'phone' => ['required'],
			'title' => ['required'],
			'position' => ['required'],
			'name' => ['required'],
			'family_name' => ['required'],
			'password' => ['required', 'min:8'],
		];
	}

	public function messages()
	{
		return [
			'email.required' => 'Veuillez renseigner l\'adresse email.',
			'email.email' => 'L\'adresse email n\'est pas au bon format.',
			'email.unique' => 'L\'adresse email doit être unique.',
			'phone.required' => 'Veuillez renseigner le numéro de téléphone.',
			'title.required' => 'Veuillez renseigner la civilité.',
			'position.required' => 'Veuillez renseigner la fonction.',
			'name.required' => 'Veuillez renseigner le nom.',
			'family_name.required' => 'Veuillez renseigner le prénom.',
			'password.required' => 'Veuillez renseigner le mot de passe.',
			'password.min' => 'Le mot de passe doit faire plus de 8 caractères.',
		];
	}

}
