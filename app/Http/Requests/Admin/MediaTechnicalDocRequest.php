<?php namespace App\Http\Requests\AdNetwork;

use App\Http\Requests\Request;

class MediaTechnicalDocRequest extends Request {

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
            'technical_doc' => ['required', 'mimes:pdf', 'max:5000'],
        ];
    }

    public function messages()
    {
        return [
            'technical_doc.required' => 'Veuillez sélectionner la documentation technique.',
            'technical_doc.mimes' => 'La documentation technique doit être un PDF.',
            'technical_doc.max' => 'La taille de la documentation technique doit être inférieure à 5 MB.'
        ];
    }

}
