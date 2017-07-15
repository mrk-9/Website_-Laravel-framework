<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class StoreFormatRequest extends Request {

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
			'name' => ['required', 'unique:format,name'],
		];
	}

	public function messages()
	{
		return [
			'name.required' => 'Veuillez saisir le nom du format.',
			'name.unique' => 'Un format de ce nom existe déjà.'
		];
	}

}
