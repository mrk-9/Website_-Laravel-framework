<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class StoreAdNetworkRequest extends Request {

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
			'corporate_name' => ['required'],
			'company_type' => ['required'],
			'address' => ['required'],
			'zipcode' => ['required'],
			'city' => ['required'],
			'phone' => ['required'],
			'email' => ['required', 'email', 'unique:ad_network,email'],
			'status' => ['required'],
			'supports' => ['required', 'integer']

		];
	}

	public function messages()
	{
		return [
			'name.required' => 'Veuillez renseigner le nom.',
			'corporate_name.required' => 'Veuillez renseigner le nom de la marque.',
			'company_type.required' => 'Veuillez renseigner le type.',
			'address.required' => 'Veuillez renseigner l\'adresse principale.',
			'zipcode.required' => 'Veuillez renseigner le code postal.',
			'city.required' => 'Veuillez renseigner la ville.',
			'phone.required' => 'Veuillez renseigner le téléphone.',
			'email.required' => 'Veuillez renseigner l\'adresse email.',
			'email.email' => 'Votre adresse email n\'est pas au bon format.',
			'email.unique' => 'L\'adresse email doit être unique.',
			'status.required' => 'Veuillez renseigner le statut.',
			'ad_network_user_id.required' => 'Veuillez renseigner le référent.',
			'supports.required' => 'Veuillez saisir le nombre de supports.',
			'supports.integer' => 'Le nombre de supports doit être un entier.'
		];
	}

}
