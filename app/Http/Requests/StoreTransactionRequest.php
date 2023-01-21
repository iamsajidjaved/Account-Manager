<?php

namespace App\Http\Requests;

use App\Models\Transaction;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreTransactionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('transaction_create');
    }

    public function rules()
    {
        return [
            'customer_name' => [
                'string',
                'max:255',
                'required',
                'unique:transactions',
            ],
            'amount' => [
                'numeric',
                'required',
                'min:0',
            ],
            'bank_id' => [
                'required',
                'integer',
            ],
            'reference' => [
                'string',
                'max:255',
                'required',
            ],
            'status' => [
                'required',
            ],
            'beneficiary_bank' => [
                'string',
                'max:255',
                'nullable',
            ],
            'withdraw_purpose' => [
                'string',
                'max:255',
                'nullable',
            ],
            'transaction_type' => [
                'required',
            ],
        ];
    }
}
