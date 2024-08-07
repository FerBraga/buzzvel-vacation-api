<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiRequest;

class UpdateVacationRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'date' => 'sometimes|date_format:Y-m-d',
            'location' => 'sometimes|string|max:255',
            'participants' => 'sometimes|array',
            'participants.*' => 'string',
        ];
    }
}
