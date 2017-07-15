<?php namespace App\Http\Requests\AdNetwork;

use App\Http\Requests\Request;

class DestroyAdPlacementRequest extends Request {
	private $indicateurs = [
		'deletion_cause' => ['required'],
	];

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
		return $this->indicateurs;
	}

	public function messages()
	{
		return [
			'deletion_cause.required' => 'Veuillez saisir le motif de suppression.'
		];
	}

}
