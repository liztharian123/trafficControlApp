<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePermitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quote_id'      => ['required', 'exists:quotes,id', 'unique:permits,quote_id'],
            'permit_number' => ['nullable', 'string', 'max:40'],
            'authority'     => ['required', 'string', 'max:80'],
            'lodged_date'   => ['required', 'date'],
            'expiry_date'   => ['nullable', 'date', 'after:lodged_date'],
            'status'        => ['required', 'in:pending,lodged,approved,rejected'],
            'document'      => ['nullable', 'file', 'mimes:pdf,jpg,png', 'max:5120'],
            'notes'         => ['nullable', 'string'],
        ];
    }
}
