<?php

namespace Modules\Rapat\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTugasPesertaRapatRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'deskripsi' => ['required', 'string', 'min:5'],
            'batas_waktu' => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
