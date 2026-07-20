<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFormOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'brand_id' => ['required', 'exists:brands,id'],

            'invoice_id' => ['nullable', 'exists:invoices,id'],

            'tanggal_order' => ['required', 'date'],

            'nama_klien' => ['required', 'string', 'max:255'],

            'lokasi_project' => ['nullable', 'string'],

            'jenis_pekerjaan' => ['nullable', 'string', 'max:255'],

            'ukuran_bangunan' => ['nullable', 'string', 'max:255'],

            'arah_mata_angin' => ['nullable', 'string', 'max:255'],

            'share_location' => ['nullable', 'string', 'max:2048'],

            'catatan_klien' => ['nullable', 'string'],

            'lingkup_pekerjaan' => ['nullable', 'array'],

            'lingkup_pekerjaan.*' => ['string'],

            'images' => ['nullable', 'array'],

            'images.*.file' => ['nullable', 'image', 'max:5120'],

            'images.*.caption' => ['nullable', 'string', 'max:255'],

            'revisions' => ['nullable', 'array'],

            'revisions.*.catatan' => ['nullable', 'string'],

            'revisions.*.file' => ['nullable', 'image', 'max:5120'],

        ];
    }
}
