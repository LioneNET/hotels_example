<?php

namespace App\Http\Requests;

use App\Enums\EventTypeEnums;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventRequest extends FormRequest
{
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
            'user_id' => [
                'nullable',
                Rule::exists(User::class, 'id')
            ],
            'type' => [
                'nullable',
                Rule::in(array_keys(EventTypeEnums::options()))
            ],
            'period' => [
                'nullable',
                'array',
                'size:2'
            ],
            'period.*' => [
                'required',
                'date'
            ],
            'timeZone' => [
                'required',
                'string'
            ],
            'sort_by' => [
                'nullable'
            ],
            'sort' => [
                'required_with:sort_by',
                Rule::in(['asc', 'desc'])
            ],
            'perPage' => [
                'nullable'
            ]
        ];
    }
}
