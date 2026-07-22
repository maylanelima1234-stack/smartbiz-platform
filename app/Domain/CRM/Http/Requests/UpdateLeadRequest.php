<?php

namespace App\Modules\CRM\Http\Requests;

class UpdateLeadRequest extends StoreLeadRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['name'] = ['sometimes', 'required', 'string', 'max:160'];
        return $rules;
    }
}
