<?php namespace App\Http\Requests\Admin;

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
			'frequency_id' => ['required'],
			'datas' => ['max:250'],
			'ad_network_id' => ['required']
		];
	}

	public function messages()
	{
		return [
			'name.required' => 'Veuillez saisir le nom du support.',
			'frequency_id.required' => 'Veuillez saisir la périodicité du média.',
			'datas.max' => 'Le champ des indicateurs est limité à 250 caractères.',
			'ad_network_id.required' => 'Veuillez saisir la régie associée au média.'
		];
	}

}
