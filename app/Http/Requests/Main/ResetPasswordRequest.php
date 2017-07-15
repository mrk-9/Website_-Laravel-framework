<?php namespace App\Http\Requests\Main;

use App\Http\Requests\Request;

class ResetPasswordRequest extends Request {

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
			'email' => ['required', 'email', 'exists:user,email'],
			'password' => ['sometimes', 'required', 'min:8', 'confirmed'],
		];
	}

	public function messages()
	{
		return [
			'email.email' => 'L\'email est invalide.',
			'email.required' => 'Veuillez saisir votre email.',
			'email.exists' => 'Aucun utilisateur existant pour l\'email donné.',
			'password.required' => 'Veuillez saisir votre mot de passe.',
			'password.min' => 'Votre mot de passe doit contenir au minimum 8 caractères.',
			'password.confirmed' => 'Les mots de passe ne sont pas identiques.',
		];
	}

}
