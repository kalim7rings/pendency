<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ViewDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
           'req_srno' => 'required',
           'dtl_srno' => 'required',
        ];
    }

    public function requestSerialNumber()
    {
        return $this->req_srno;
    }

    public function detailsSerialNumber()
    {
        return $this->dtl_srno;

    }
}
