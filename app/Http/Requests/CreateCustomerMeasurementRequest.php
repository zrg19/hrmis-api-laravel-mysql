<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCustomerMeasurementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:customer_measurements,code',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'kameezlength' => 'nullable|string|max:10',
            'teera' => 'nullable|string|max:10',
            'baazo' => 'nullable|string|max:10',
            'chest' => 'nullable|string|max:10',
            'neck' => 'nullable|string|max:10',
            'daman' => 'nullable|string|max:10',
            'kera' => 'nullable|string|max:20',
            'shalwar' => 'nullable|string|max:10',
            'pancha' => 'nullable|string|max:10',
            'pocket' => 'nullable|string|max:10',
            'note' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Customer name is required.',
            'code.required' => 'Customer code is required.',
            'code.unique' => 'This customer code already exists.',
            'phone.required' => 'Phone number is required.',
        ];
    }
}
