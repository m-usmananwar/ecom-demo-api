<?php

namespace App\Http\Requests\Ads;

use Illuminate\Foundation\Http\FormRequest;

class AdStoreRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'description' => ['required', 'min:10', 'max:255'],
            'car_make' => ['required', 'min:4', 'max:20'],
            'car_model' => ['required', 'min:4', 'max:20'],
            'car_color' => ['required', 'min:4', 'max:20'],
            'car_millage' => ['required', 'min:4', 'max:20'],
        ];
    }
}
