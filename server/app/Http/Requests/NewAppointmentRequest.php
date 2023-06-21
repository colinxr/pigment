<?php

namespace App\Http\Requests;

use Illuminate\Support\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class NewAppointmentRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'description' => 'sometimes',
            'startDateTime' => 'required|date_format:Y-m-d\TH:i:sO',
            'duration' => 'required',
            'price' => 'required',
            'deposit' => 'sometimes',
        ];
    }

    protected function passedValidation(): void
    {
        $endDateTime = Carbon::parse($this->startDateTime)->addHours($this->duration);
        $this->merge(['endDateTime' => $endDateTime]);
    }
}
