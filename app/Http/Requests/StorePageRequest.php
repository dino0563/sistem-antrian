<?php

// app/Http/Requests/StorePageRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama' => 'required',
            'alamat' => 'required',
            'instagram' => 'required',
            'website' => 'required',
            'logo' => 'image|mimes:jpeg,png,jpg,gif', // Validasi unggahan gambar
        ];
    }
}
