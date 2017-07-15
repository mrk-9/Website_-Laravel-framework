<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class UpdateBuyerRequest extends Request {

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
			'name' => ['required'],
			'company_type' => ['required'],
			'address' => ['required'],
			'zipcode' => ['required'],
			'city' => ['required'],
			'phone' => ['required'],
			'email' => ['required', 'email', 'unique:buyer,email,' . $this->route()->parameter('buyer')->id],
			'status' => ['required'],
			'type' => ['required'],
			'user_id' => ['unique:buyer,user_id,' . $this->route()->parameter('buyer')->id]

		];
	}

	public function messages()
	{
		return [
			'name.required' => 'Veuillez renseigner le nom.',
			'company_type.required' => 'Veuillez renseigner le type.',
			'address.required' => 'Veuillez renseigner l\'adresse principale.',
			'zipcode.required' => 'Veuillez renseigner le code postal.',
			'city.required' => 'Veuillez renseigner la ville.',
			'phone.required' => 'Veuillez renseigner le téléphone.',
			'email.required' => 'Veuillez renseigner l\'adresse email.',
			'email.email' => 'Votre adresse email n\'est pas au bon format.',
			'email.unique' => 'L\'adresse email doit être unique.',
			'status.required' => 'Veuillez renseigner le statut.',
			'activity.required' => 'Veuillez renseigner le nom.',
			'type.required' => 'Veuillez renseigner la catégorie de la société (annonceur / agence).',
			'user_id.unique' => 'Cet utilisateur est déjà le référent d\'une autre société. Veuillez vérifier votre saisie.'
		];
	}

}
