<?php namespace App\Http\Requests\Main;

use App\Http\Requests\Request;

class CreateBuyerRequest extends Request {

	protected $rules = [
		'title' => ['required', 'in:"M.","Mme.","Mlle."'],
		'family_name' => ['required', 'max:255'],
		'name' => ['required', 'max:255'],
		'function' => ['required', 'max:255'],
		'email' => ['required', 'email', 'unique:user,email', 'max:255'],
		'phone' => ['required', 'regex:/^0[1-9]([-. ]?[0-9]{2}){4}$/'],
		'password' => ['required', 'confirmed', 'min:6', 'max:255'],
		'password_confirmation' => ['required'],

		'buyer_type' => ['required', 'in:agency,advertiser'],
		'buyer_name' => ['required', 'max:255'],
		'buyer_company_type' => ['required', 'in:SARL,SA,EURL'],
		'buyer_activity' => ['required_if:buyer_type,advertiser', 'max:255'],
		'buyer_address' => ['required', 'max:255'],
		'buyer_zipcode' => ['required', 'regex:/^((0[1-9])|([1-8][0-9])|(9[0-8]|(2A)|(2B)))[0-9]{3}$/'],
		'buyer_city' => ['required', 'max:255'],
		'buyer_phone' => ['required', 'regex:/^0[1-9]([-. ]?[0-9]{2}){4}$/'],
		'buyer_email' => ['required', 'email', 'unique:buyer,email', 'max:255'],
		'buyer_customers' => ['required_if:buyer_type,agency'],
	];

	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return $this->rules;
	}

	public function messages()
	{
		return [
			'title.required' => 'Veuillez renseigner votre civilité.',
			'title.in' => 'La civilité renseignée est invalide',
			'name.required' => 'Veuillez renseigner votre prénom.',
			'name.max' => 'Le champs prénom ne peut pas faire plus de 255 caractères',
			'family_name.required' => 'Veuillez renseigner votre nom.',
			'family_name.max' => 'Le champs nom ne peut pas faire plus de 255 caractères',
			'function.required' => 'Veuillez renseigner votre fonction.',
			'function.max' => 'Le champs fonction ne peut pas faire plus de 255 caractères',
			'email.required' => 'Veuillez renseigner votre adresse email.',
			'email.email' => 'L\'adresse fournie n\'est pas au bon format.',
			'email.unique' => 'Vous êtes déjà inscrit.',
			'email.max' => 'Le champs mail ne peut pas faire plus de 255 caractères',
			'phone.required' => 'Veuillez renseigner votre numéro de téléphone.',
			'phone.regex' => 'Le numéro de téléphone n\'est pas valide. Il doit être sous la forme 0182522035',
			'password.required' => 'Veuillez renseigner votre mot de passe.',
			'password.min' => 'Le champs mot de passe doit faire plus de 6 caractères',
			'password.max' => 'Le champs mot de passe ne peut pas faire plus de 255 caractères',
			'password.confirmed' => 'Vos mots de passe ne correspondent pas.',
			'password_confirmation.required' => 'Veuillez renseigner à nouveau votre mot de passe.',

			'buyer_type.required' => 'Veuillez renseigner votre type de compte',
			'buyer_type.in' => 'Type de compte invalide',
			'buyer_name.required' => 'Veuillez renseigner le nom de la société.',
			'buyer_name.max' => 'Le champs nom de la société ne peut pas faire plus de 255 caractères',
			'buyer_company_type.required' => 'Veuillez renseigner le type de société.',
			'buyer_company_type.in' => 'Type de société invalide',
			'buyer_activity.required_if' => 'Veuillez renseigner le secteur d\'activité de la société.',
			'buyer_activity.max' => 'Le champs secteur d\'activité de la société ne peut pas faire plus de 255 caractères',
			'buyer_address.required' => 'Veuillez renseigner l\'adresse de la société.',
			'buyer_address.max' => 'Le champs adresse de la société ne peut pas faire plus de 255 car  actères',
			'buyer_zipcode.required' => 'Veuillez renseigner le code postal de la société.',
			'buyer_zipcode.regex' => 'Le code postal n\'est pas valide, il doit être sous la forme 75000',
			'buyer_city.required' => 'Veuillez renseigner la ville de la société.',
			'buyer_city.max' => 'Le champs ville de la société ne peut pas faire plus de 255 caractères',
			'buyer_phone.required' => 'Veuillez renseigner le téléphone de la société.',
			'buyer_phone.regex' => 'Le numéro de téléphone de la société n\'est pas valide. Il doit être sous la forme 0182522035',
			'buyer_email.required' => 'Veuillez renseigner l\'adresse email de la société.',
			'buyer_email.email' => 'L\'adresse email de la société n\'est pas au bon format.',
			'buyer_email.unique' => 'La société êtes déjà inscrite.',
			'buyer_email.max' => 'Le champs email de la société ne peut pas faire plus de 255 caractères',
			'buyer_customers.required_if' => 'Veuillez renseigner les mandats de votre société.',
		];
	}

}
