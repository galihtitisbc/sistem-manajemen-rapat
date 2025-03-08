<?php
namespace Modules\Rapat\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRapatRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id'         => 'required|exists:users,id',
            'pimpinan_id'     => 'required|exists:users,id',
            'kepanitiaan_id'  => 'nullable|exists:kepanitiaans,id',
            'peserta_rapat'   => 'required|array',
            'peserta_rapat.*' => 'exists:users,id',
            'notulis_id'      => 'required|exists:users,id',
            'nomor_surat'     => 'required|string|max:255',
            'waktu_mulai'     => 'required|date_format:Y-m-d H:i:s',
            'waktu_selesai'   => 'required|date_format:Y-m-d H:i:s|after:waktu_mulai',
            'agenda_rapat'    => 'required|string',
            'tempat'          => 'required|string|max:255',
            'lampiran'        => 'nullable|file|mimes:jpg,jpeg,png,doc,docx,xls,xlsx,pdf,txt|max:2048',
        ];
    }
    public function validated($data = [])
    {
        return validator($data, $this->rules())->validate();
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
