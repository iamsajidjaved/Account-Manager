@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.transaction.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.transactions.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required">{{ trans('cruds.transaction.fields.transaction_type') }}</label>
                @foreach(App\Models\Transaction::TRANSACTION_TYPE_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('transaction_type') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="transaction_type_{{ $key }}" name="transaction_type" value="{{ $key }}" {{ old('transaction_type', 'Deposit') === (string) $key ? 'checked' : '' }} required>
                        <label class="form-check-label" for="transaction_type_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('transaction_type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('transaction_type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.transaction.fields.transaction_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="customer_name">{{ trans('cruds.transaction.fields.customer_name') }}</label>
                <input class="form-control {{ $errors->has('customer_name') ? 'is-invalid' : '' }}" type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', '') }}" required>
                @if($errors->has('customer_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('customer_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.transaction.fields.customer_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="amount">{{ trans('cruds.transaction.fields.amount') }}</label>
                <input class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="number" name="amount" id="amount" value="{{ old('amount', '') }}" step="0.0001" required>
                @if($errors->has('amount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('amount') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.transaction.fields.amount_helper') }}</span>
            </div>
            <div class="form-group beneficiary-bank" style="display: none;">
                <label for="beneficiary_bank">{{ trans('cruds.transaction.fields.beneficiary_bank') }}</label>
                <input class="form-control {{ $errors->has('beneficiary_bank') ? 'is-invalid' : '' }}" type="text" name="beneficiary_bank" id="beneficiary_bank" value="{{ old('beneficiary_bank', '') }}">
                @if($errors->has('beneficiary_bank'))
                    <div class="invalid-feedback">
                        {{ $errors->first('beneficiary_bank') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.transaction.fields.beneficiary_bank_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="bank_id">{{ trans('cruds.transaction.fields.bank') }}</label>
                <select class="form-control select2 {{ $errors->has('bank') ? 'is-invalid' : '' }}" name="bank_id" id="bank_id" required>
                    @foreach($banks as $id => $entry)
                        <option value="{{ $id }}" {{ old('bank_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
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
                <input class="form-control {{ $errors->has('reference') ? 'is-invalid' : '' }}" type="text" name="reference" id="reference" value="{{ old('reference', '') }}" required>
                @if($errors->has('reference'))
                    <div class="invalid-feedback">
                        {{ $errors->first('reference') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.transaction.fields.reference_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="remarks">{{ trans('cruds.transaction.fields.remarks') }}</label>
                <textarea class="form-control {{ $errors->has('remarks') ? 'is-invalid' : '' }}" name="remarks" id="remarks">{{ old('remarks') }}</textarea>
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

@section('scripts')
@parent
<script>
        $(document).ready(function() {
        $("input[name='transaction_type']").click(function() {
            const transaction_type = $(this).val();

            if(transaction_type=="Deposit"){
                $("div.beneficiary-bank").hide();
            }else if(transaction_type=="Withdrawal"){
                $("div.beneficiary-bank").show();
            }
        });
    });
</script>
@endsection