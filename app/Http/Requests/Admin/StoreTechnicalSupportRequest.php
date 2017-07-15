<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class StoreTechnicalSupportRequest extends Request {

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
			'description' => ['required'],
			'price' => ['required', 'regex:/^[1-9]\d*((\.|,)\d+)?$/'],
		];
	}

	public function messages()
	{
		return [
			'name.required' => 'Veuillez saisir le nom du support technique.',
			'description.required' => 'Veuillez saisir la description du support technique.',
			'price.required' => 'Veuillez saisir le prix du support technique.',
			'price.regex' => 'Le prix doit avoir un format monétaire.',
		];
	}

}
