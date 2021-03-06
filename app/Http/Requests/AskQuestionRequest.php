<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\title;

class AskQuestionRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255', new title],
            'body' => 'required',
            'tags.*' => 'nullable|alpha_dash|distinct',
            'newTags.*' => 'nullable|alpha_dash|distinct',
            'removedTags.*' => 'nullable|integer',
            'removedImages.*' => 'nullable|integer',
            'images.*' => 'nullable|mimes:jpeg,png'
        ];
    }
}
