<?php namespace App\Http\Requests\AdNetwork;

use App\Http\Requests\Request;

class UpdateMediaRequest extends Request {

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
			'support_id' => ['required'],
			'theme_id' => ['sometimes', 'required'],
			'category_id' => ['sometimes', 'required'],
			'frequency_id' => ['required'],
			'broadcasting_area_id' => ['required'],
			'target_id' => ['required'],
			'datas' => ['max:250']
		];
	}

	public function messages()
	{
		return [
			'name.required' => 'Veuillez saisir le nom du support.',
			'support_id.required' => 'Veuillez sélectionner le type de support.',
			'theme_id.required' => 'Veuillez sélectionner la thématique.',
			'category_id.required' => 'Veuillez sélectionner la catégorie.',
			'frequency_id.required' => 'Veuillez sélectionner la périodicité du média.',
			'broadcasting_area_id.required' => 'Veuillez sélectionner la zone de diffusion',
			'target_id.required' => 'Veuillez sélectionner les cibles concernées.',
			'datas.max' => 'Le champ des indicateurs est limité à 250 caractères.',
		];
	}

}
