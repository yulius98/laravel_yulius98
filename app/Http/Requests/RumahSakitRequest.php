<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RumahSakitRequest extends FormRequest
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
        $rumahSakitId = $this->route('rumah_sakit') ?? $this->route('id');

        return [
            'nama_rumah_sakit' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tbl_rumah_sakits', 'nama_rumah_sakit')->ignore($rumahSakitId)
            ],
            'alamat' => 'required|string|max:500',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('tbl_rumah_sakits', 'email')->ignore($rumahSakitId)
            ],
            'telepon' => 'required|string|max:20',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nama_rumah_sakit.required' => 'Nama rumah sakit wajib diisi.',
            'nama_rumah_sakit.unique' => 'Nama rumah sakit sudah ada.',
            'alamat.required' => 'Alamat wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'telepon.required' => 'Telepon wajib diisi.',
        ];
    }
}
