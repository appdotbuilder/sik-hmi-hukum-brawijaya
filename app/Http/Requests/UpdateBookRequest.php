<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()?->canManageUsers() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_available_print' => 'boolean',
            'is_available_digital' => 'boolean',
            'digital_url' => 'nullable|url|required_if:is_available_digital,true',
            'loan_duration_days' => 'required|integer|min:1|max:90',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul buku wajib diisi.',
            'author.required' => 'Nama pengarang wajib diisi.',
            'digital_url.url' => 'URL digital harus berupa URL yang valid.',
            'digital_url.required_if' => 'URL digital wajib diisi jika buku tersedia secara digital.',
            'loan_duration_days.required' => 'Durasi peminjaman wajib diisi.',
            'loan_duration_days.min' => 'Durasi peminjaman minimal 1 hari.',
            'loan_duration_days.max' => 'Durasi peminjaman maksimal 90 hari.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->boolean('is_available_print') && !$this->boolean('is_available_digital')) {
                $validator->errors()->add('availability', 'Buku harus tersedia minimal dalam satu format (cetak atau digital).');
            }
        });
    }
}