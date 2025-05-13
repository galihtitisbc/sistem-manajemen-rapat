<?php
namespace Modules\Rapat\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KepanitiaanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nama_kepanitiaan'  => 'required|string|max:255',
            'deskripsi'         => 'required|string',
            'tanggal_mulai'     => 'required|date',
            'tanggal_berakhir'  => 'required|date|after_or_equal:tanggal_mulai',
            'tujuan'            => 'required|string|max:255',
            'pimpinan_username' => 'required|exists:pegawais,username',
            'peserta'           => 'required|array',
            'peserta.*'         => 'exists:pegawais,username',
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
