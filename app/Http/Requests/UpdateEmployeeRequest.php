<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Employee;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $employeeId = $this->route('employee')->id;

        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('employees', 'email')->ignore($this->route('employee')->id)->whereNull('deleted_at'),],
            'phone' => ['required', 'string', 'regex:/^[0-9+\s\-]{8,20}$/'],
            'gender' => ['required', 'in:male,female,other'],
            'skills' => ['required', 'array', 'min:1'],
            'skills.*' => ['string',Rule::in(Employee::SKILLS)],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'joining_date' => ['required', 'date'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'country_id' => ['required', 'exists:countries,id'],
            'state_id' => ['required', 'exists:states,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}