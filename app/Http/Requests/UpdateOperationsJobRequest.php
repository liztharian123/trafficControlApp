<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOperationsJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'scheduled_date' => ['required', 'date'],
            'shift_start'    => ['required', 'date_format:H:i'],
            'shift_end'      => ['required', 'date_format:H:i', 'after:shift_start'],
            'site_address'   => ['required', 'string', 'max:255'],
            'notes'          => ['nullable', 'string'],
        ];
    }
}
