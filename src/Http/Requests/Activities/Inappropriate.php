<?php

namespace Blog\Http\Requests\Activities;

use Illuminate\Foundation\Http\FormRequest;
use Blog\Models\Activity;

class Inappropriate extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() &&  auth()->user()->can('create', Activity::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'activityable_type' => 'required|max:191',
            'activityable_id' => 'required|numeric',
            'type' => 'required|max:50',
            'reason' => 'required|max:100',
            'message' => 'nullable|max:250|string'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [

        ];
    }

}
