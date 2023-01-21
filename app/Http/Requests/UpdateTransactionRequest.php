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
            'transaction_type' => [
                'required',
            ],
            'customer_name' => [
                'string',
                'max:255',
                'required',
                'unique:transactions,customer_name,' . request()->route('transaction')->id,
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
            'deposit_no' => [
                'string',
                'max:255',
                'nullable',
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
            'remarks' => [
                'string',
                'max:255',
                'nullable',
            ],
        ];
    }
}
