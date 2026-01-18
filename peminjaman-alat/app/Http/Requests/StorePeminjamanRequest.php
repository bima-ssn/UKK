<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePeminjamanRequest extends FormRequest
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
        return [
            'user_id' => 'required|exists:users,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'alat' => 'required|array|min:1',
            'alat.*.id' => 'required|exists:alat,id',
            'alat.*.jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'Peminjam harus dipilih.',
            'user_id.exists' => 'Peminjam tidak valid.',
            'tanggal_pinjam.required' => 'Tanggal pinjam harus diisi.',
            'tanggal_pinjam.date' => 'Tanggal pinjam harus berupa tanggal yang valid.',
            'tanggal_kembali.required' => 'Tanggal kembali harus diisi.',
            'tanggal_kembali.date' => 'Tanggal kembali harus berupa tanggal yang valid.',
            'tanggal_kembali.after' => 'Tanggal kembali harus setelah tanggal pinjam.',
            'alat.required' => 'Minimal harus memilih satu alat.',
            'alat.array' => 'Format alat tidak valid.',
            'alat.min' => 'Minimal harus memilih satu alat.',
            'alat.*.id.required' => 'Alat harus dipilih.',
            'alat.*.id.exists' => 'Alat tidak valid.',
            'alat.*.jumlah.required' => 'Jumlah harus diisi.',
            'alat.*.jumlah.integer' => 'Jumlah harus berupa angka.',
            'alat.*.jumlah.min' => 'Jumlah minimal 1.',
        ];
    }
}
