<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class StoreTemplateRequest extends Request {

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
			'cover' => ['required', 'mimes:jpeg,png', 'max:1000'],
		];
	}

	public function messages()
	{
		return [
			'name.required' => 'Veuillez saisir le nom du gabarit.',
			'description.required' => 'Veuillez saisir la description du gabarit.',
			'cover.required' => 'Veuillez sélectionner la couverture du gabarit.',
			'cover.mimes' => 'Le visuel de couverture doit être au format jpeg ou png.',
			'cover.max' => 'L\'image de couverture doit être inférieure à 1 MB.'
		];
	}

}
