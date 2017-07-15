<?php namespace App\Http\Requests\AdNetwork;

use App\Http\Requests\Request;

class MediaCoverRequest extends Request {

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
            'cover' => ['required', 'mimes:jpeg,png', 'max:1000'],
        ];
    }

    public function messages()
    {
        return [
            'cover.required' => 'Veuillez sélectionner L\'image de couverture.',
            'cover.mimes' => 'L\'image de couverture doit être de format jpeg ou png.',
            'cover.max' => 'L\'image de couverture doit être inférieure à 1 MB.'
        ];
    }

}
