<?php

namespace App\Http\Requests;

use App\Models\Bank;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreBankRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('bank_create');
    }

    public function rules()
    {
        return [
            'bank_name' => [
                'string',
                'max:255',
                'required',
                'unique:banks',
            ],
            'balance' => [
                'numeric',
                'required',
                'min:0',
            ],
            'country_id' => [
                'required',
                'integer',
            ],
            'group_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
