<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOperationsJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'permit_id'      => ['required', 'exists:permits,id', 'unique:operations_jobs,permit_id'],
            'scheduled_date' => ['required', 'date', 'after_or_equal:today'],
            'shift_start'    => ['required', 'date_format:H:i'],
            'shift_end'      => ['required', 'date_format:H:i', 'after:shift_start'],
            'site_address'   => ['required', 'string', 'max:255'],
            'notes'          => ['nullable', 'string'],
        ];
    }
}
