<?php namespace App\Http\Requests\Main;

use App\Http\Requests\Request;

class SaveAgencyRequest extends Request {

	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			'ad_network_name' => ['required'],
			'corporate_name' => ['required'],
			'company_type' => ['required'],
			'address' => ['required'],
			'address2' => [],
			'zipcode' => ['required'],
			'city' => ['required'],
			'phone' => ['required'],
			'user_name' => ['required'],
			'family_name' => ['required'],
			'title' => ['required', 'in:"M.","Mme.","Mlle."'],
			'user_email' => ['required', 'email', 'unique:ad_network_user,email'],
			'position' => ['required'],
		];
	}

	public function messages()
	{
		return [
			'ad_network_name.required' => 'Veuillez renseigner le nom de votre régie.',
			'corporate_name.required' => 'Veuillez renseigner une raison sociale.',
			'company_type.required' => 'Veuillez renseigner le type de société.',
			'address.required' => 'Veuillez renseigner votre adresse.',
			'zipcode.required' => 'Veuillez renseigner votre code postal.',
			'city.required' => 'Veuillez renseigner votre ville.',
			'phone.required' => 'Veuillez renseigner votre téléphone.',
			'name.required' => 'Veuillez renseigner votre prénom.',
			'family_name.required' => 'Veuillez renseigner votre nom.',
			'title.required' => 'Veuillez renseigner votre civilité.',
			'title.in' => 'La civilité renseignée est invalide',
			'user_email.required' => 'Veuillez renseigner votre adresse email.',
			'user_email.email' => 'L\'adresse fournie n\'est pas au bon format.',
			'user_email.unique' => 'Vous êtes déjà inscrit sur la liste d\'attente.',
			'position.required' => 'Veuillez renseigner votre fonction.',
		];
	}

}
