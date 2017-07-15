<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Request as RequestFacade;

class UpdateOfferRequest extends Request {

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
			'order_file' => ['mimes:jpeg,bmp,png,pdf']
		];
	}

	/**
	 * Defines form messages
	 *
	 * @return array
	 */
	public function messages()
	{
		return [
			'order_file.mimes' => 'Le fichier à envoyer doit être dans l\'un de ces formats : png, jpeg, bmp, pdf',
		];
	}

}
