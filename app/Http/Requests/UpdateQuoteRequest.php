<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id'    => ['required', 'exists:clients,id'],
            'reference_no' => ['required', 'string', 'max:20', Rule::unique('quotes', 'reference_no')->ignore($this->quote)],
            'site_address' => ['required', 'string', 'max:255'],
            'description'  => ['required', 'string'],
            'start_date'   => ['required', 'date'],
            'end_date'     => ['required', 'date', 'after_or_equal:start_date'],
            'amount'       => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'status'       => ['required', 'in:draft,sent,approved,rejected'],
        ];
    }
}
