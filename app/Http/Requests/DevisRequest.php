<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DevisRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:150'],
            'telephone' => ['required', 'string', 'max:30', 'regex:/^[0-9+().\/\s-]{8,30}$/'],
            'email' => ['nullable', 'email:rfc', 'max:190'],
            'prestation' => ['nullable', 'string', 'max:80'],
            'commune' => ['nullable', 'string', 'max:80'],
            'message' => ['nullable', 'string', 'max:3000'],
            'volume_estime' => ['nullable', 'string', 'max:120'],
            'source' => ['nullable', 'string', 'max:80'],
            'societe' => ['prohibited'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Merci d’indiquer votre nom.',
            'telephone.required' => 'Un numéro de téléphone est nécessaire pour vous rappeler.',
            'telephone.regex' => 'Ce numéro de téléphone semble invalide.',
            'email.email' => 'Cette adresse e-mail semble invalide.',
            'societe.prohibited' => 'Votre demande n’a pas pu être envoyée.',
        ];
    }
}
