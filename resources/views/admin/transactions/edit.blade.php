@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.transaction.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.transactions.update", [$transaction->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="customer_name">{{ trans('cruds.transaction.fields.customer_name') }}</label>
                <input class="form-control {{ $errors->has('customer_name') ? 'is-invalid' : '' }}" type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', $transaction->customer_name) }}" required>
                @if($errors->has('customer_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('customer_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.transaction.fields.customer_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="amount">{{ trans('cruds.transaction.fields.amount') }}</label>
                <input class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="number" name="amount" id="amount" value="{{ old('amount', $transaction->amount) }}" step="0.0001" required>
                @if($errors->has('amount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('amount') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.transaction.fields.amount_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="bank_id">{{ trans('cruds.transaction.fields.bank') }}</label>
                <select class="form-control select2 {{ $errors->has('bank') ? 'is-invalid' : '' }}" name="bank_id" id="bank_id" required>
                    @foreach($banks as $id => $entry)
                        <option value="{{ $id }}" {{ (old('bank_id') ? old('bank_id') : $transaction->bank->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('bank'))
                    <div class="invalid-feedback">
                        {{ $errors->first('bank') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.transaction.fields.bank_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="reference">{{ trans('cruds.transaction.fields.reference') }}</label>
                <input class="form-control {{ $errors->has('reference') ? 'is-invalid' : '' }}" type="text" name="reference" id="reference" value="{{ old('reference', $transaction->reference) }}" required>
                @if($errors->has('reference'))
                    <div class="invalid-feedback">
                        {{ $errors->first('reference') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.transaction.fields.reference_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.transaction.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status">
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Transaction::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $transaction->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.transaction.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="deposit_no">{{ trans('cruds.transaction.fields.deposit_no') }}</label>
                <input class="form-control {{ $errors->has('deposit_no') ? 'is-invalid' : '' }}" type="text" name="deposit_no" id="deposit_no" value="{{ old('deposit_no', $transaction->deposit_no) }}">
                @if($errors->has('deposit_no'))
                    <div class="invalid-feedback">
                        {{ $errors->first('deposit_no') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.transaction.fields.deposit_no_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="approver_id">{{ trans('cruds.transaction.fields.approver') }}</label>
                <select class="form-control select2 {{ $errors->has('approver') ? 'is-invalid' : '' }}" name="approver_id" id="approver_id">
                    @foreach($approvers as $id => $entry)
                        <option value="{{ $id }}" {{ (old('approver_id') ? old('approver_id') : $transaction->approver->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('approver'))
                    <div class="invalid-feedback">
                        {{ $errors->first('approver') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.transaction.fields.approver_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="remarks">{{ trans('cruds.transaction.fields.remarks') }}</label>
                <textarea class="form-control {{ $errors->has('remarks') ? 'is-invalid' : '' }}" name="remarks" id="remarks">{{ old('remarks', $transaction->remarks) }}</textarea>
                @if($errors->has('remarks'))
                    <div class="invalid-feedback">
                        {{ $errors->first('remarks') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.transaction.fields.remarks_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection