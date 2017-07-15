<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class UpdateAdNetworkRequest extends Request {

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
			'email' => ['required', 'email', 'unique:ad_network,email,' . $this->route()->parameter('ad_network')->id],
			'status' => ['required'],
			'ad_network_user_id' => ['required'],
			'supports' => ['required', 'integer'],
			'deposit_percent' => ['required', 'min:0', 'max:100']
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
			'supports.integer' => 'Le nombre de supports doit être un entier.',
			'deposit_percent.required' => 'Veuillez saisir le % d\'accompte.',
			'deposit_percent.max' => 'Le % d\'accompte doit être entre 0 et 100.',
			'deposit_percent.min' => 'Le % d\'accompte doit être entre 0 et 100.',
		];
	}

}
