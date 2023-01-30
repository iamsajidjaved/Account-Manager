<?php

namespace App\Http\Requests;

use App\Models\Transaction;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTransactionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('transaction_edit');
    }

    public function rules()
    {
        return [
            'customer_name' => [
                'string',
                'max:255',
                'required',
            ],
            'amount' => [
                'numeric',
                'required',
                'min:0',
            ],
            'beneficiary_bank' => [
                'string',
                'max:255',
                'nullable',
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
                'string',
                'max:255',
                'required',
            ],
            'approver_remarks' => [
                'string',
                'max:255',
                'nullable',
            ],
            'approve_datetime' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
        ];
    }
}
