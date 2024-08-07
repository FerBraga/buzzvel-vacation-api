<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiRequest;

class CreateVacationRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date_format:Y-m-d',
            'location' => 'required|string|max:255',
            'participants' => 'sometimes|array',
            'participants.*' => 'string',
        ];
    }
}
