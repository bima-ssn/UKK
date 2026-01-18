<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePengembalianRequest extends FormRequest
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
            'peminjaman_id' => 'required|exists:peminjaman,id|unique:pengembalian,peminjaman_id',
            'tanggal_dikembalikan' => 'required|date',
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
            'peminjaman_id.required' => 'Peminjaman harus dipilih.',
            'peminjaman_id.exists' => 'Peminjaman tidak valid.',
            'peminjaman_id.unique' => 'Peminjaman ini sudah dikembalikan.',
            'tanggal_dikembalikan.required' => 'Tanggal dikembalikan harus diisi.',
            'tanggal_dikembalikan.date' => 'Tanggal dikembalikan harus berupa tanggal yang valid.',
        ];
    }
}
