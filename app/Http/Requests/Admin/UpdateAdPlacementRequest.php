<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\AdPlacement;

class UpdateAdPlacementRequest extends Request {

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
			'price' => ['required', 'regex:/^[1-9]\d*((\.|,)\d+)?$/'],
			// Sadly required_without only accept fields and not field values.
			'minimum_price' => [
				'required_if:type,' . AdPlacement::TYPE_AUCTION . ',' . AdPlacement::TYPE_OFFER . ',' . AdPlacement::TYPE_HYBRID,
				'regex:/^[1-9]\d*((\.|,)\d+)?$/',
				'less_than:price'
			],
			'technical_deadline' => ['sometimes', 'date_format:d/m/Y H:i', 'after_if_not_empty:ending_at'],
			'locking_up' => ['sometimes', 'date_format:d/m/Y H:i'],
			'broadcasting_date' => ['sometimes', 'date_format:d/m/Y', 'after:04/09/2016'],
			'type' => [
				'required',
				'in:' . AdPlacement::TYPE_AUCTION
					  . ',' . AdPlacement::TYPE_OFFER
					  . ',' . AdPlacement::TYPE_BOOKING
					  . ',' . AdPlacement::TYPE_HYBRID
			],
			'starting_at' => ['required', 'date_format:d/m/Y H:i'],
			'ending_at' => ['required', 'date_format:d/m/Y H:i', 'after_if_not_empty:starting_at'],
			'media_id' => ['required', 'integer'],
			'edition' => ['sometimes', 'integer'],
			'format_id' => ['required']
		];
	}

	public function messages()
	{
		return [
			'name.required' => 'Veuillez saisir le nom de l\'emplacement.',
			'name.unique' => 'Le nom de l\'emplacement est déjà pris.',
			'price.required' => 'Veuillez saisir le prix de l\'emplacement.',
			'price.regex' => 'Le prix doit avoir un format monétaire.',
			'minimum_price.required' => 'Veuillez saisir le prix minimum de l\'emplacement.',
			'minimum_price.less_than' => 'Le prix minimum doît être inférieur au prix.',
			'minimum_price.regex' => 'Le prix minimum doit avoir un format monétaire.',
			'minimum_price.required_if' => 'Le prix minimum est requis avec ce type d\'emplacement.',
			'technical_deadline.date_format' => 'La date de rendu des éléments techniques doit-être au format jj/mm/yyyy 00:00:00',
			'technical_deadline.after_if_not_empty' => 'La date de rendu des éléments techniques doit-être supérieure à celle de fin de vente.',
			'locking_up.date_format' => 'La date de bouclage doit-être au format jj/mm/yyyy 00:00:00',
			'locking_up.after_if_not_empty' => 'La date de bouclage doit-être supérieure à celle du rendu des éléments techniques.',
			'broadcasting_date.date_format' => 'La date de publication doit-être au format jj/mm/yyyy 00:00:00',
			'type.required' => 'Veuillez saisir le type de l\'emplacement.',
			'type.in' => 'Le type sélectionné ne correspond pas à un type existant.',
			'starting_at.required' => 'Veuillez sélectionner la date de début.',
			'starting_at.date_format' => 'La date de début doit-être au format jj/mm/yyyy 00:00:00',
			'ending_at.required' => 'Veuillez sélectionner la date de fin.',
			'ending_at.date_format' => 'La date de fin doit-être au format jj/mm/yyyy 00:00:00',
			'ending_at.after_if_not_empty' => 'La date de fin de vente doit être supérieure à celle du début de vente.',
			'media_id.required' => 'Veuillez saisir le média associé.',
			'media_id.integer' => 'Le format du média sélectionné est incorrect.',
			'edition.integer' => 'Le numéro d\'édition doit être un entier.',
			'format_id.required' => 'Veuillez indiquer le format du support.',
			'broadcasting_date.after' => 'La date de publication doit être supérieure au 04/09/2016.'
		];
	}

}
