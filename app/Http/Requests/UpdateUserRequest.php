<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->route('user');
        $authUser = auth()->user();
        
        // Users can edit their own profile
        if ($authUser && $authUser->id === $user->id) {
            return true;
        }
        
        // Admins and pengurus can edit other users
        return $authUser?->canManageUsers() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->route('user');
        
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'level' => ['sometimes', Rule::in([User::LEVEL_ADMINISTRATOR, User::LEVEL_PENGURUS, User::LEVEL_KADER])],
            'nik' => ['nullable', 'string', Rule::unique('users')->ignore($user->id)],
            'komisariat' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'pt' => 'required|string|max:255',
            'golongan_darah' => ['nullable', Rule::in(User::GOLONGAN_DARAH_OPTIONS)],
            'no_whatsapp' => 'nullable|string|max:20',
            'alamat_malang' => 'nullable|string',
            'is_verified' => 'sometimes|boolean',
            'profile_completed' => 'sometimes|boolean',
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
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
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
        // Remove password if not provided
        if (empty($this->password)) {
            $this->request->remove('password');
            $this->request->remove('password_confirmation');
        }
        
        // Only allow level changes for administrators
        $authUser = auth()->user();
        if (!$authUser || !$authUser->isAdministrator()) {
            $this->request->remove('level');
            $this->request->remove('is_verified');
        }
    }
}