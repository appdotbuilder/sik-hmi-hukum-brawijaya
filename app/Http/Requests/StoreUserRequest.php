<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'level' => ['required', Rule::in([User::LEVEL_ADMINISTRATOR, User::LEVEL_PENGURUS, User::LEVEL_KADER])],
            'nik' => 'nullable|string|unique:users,nik',
            'komisariat' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'pt' => 'required|string|max:255',
            'golongan_darah' => ['nullable', Rule::in(User::GOLONGAN_DARAH_OPTIONS)],
            'no_whatsapp' => 'nullable|string|max:20',
            'alamat_malang' => 'nullable|string',
            'is_verified' => 'boolean',
            'profile_completed' => 'boolean',
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
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'level.required' => 'Level pengguna wajib dipilih.',
            'level.in' => 'Level pengguna tidak valid.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'komisariat.required' => 'Komisariat wajib diisi.',
            'jurusan.required' => 'Jurusan wajib diisi.',
            'pt.required' => 'Perguruan Tinggi wajib diisi.',
            'golongan_darah.in' => 'Golongan darah tidak valid.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'komisariat' => $this->komisariat ?? 'Hukum Brawijaya',
            'jurusan' => $this->jurusan ?? 'Hukum',
            'pt' => $this->pt ?? 'Universitas Brawijaya',
            'is_verified' => $this->boolean('is_verified', false),
            'profile_completed' => $this->boolean('profile_completed', false),
        ]);
    }
}