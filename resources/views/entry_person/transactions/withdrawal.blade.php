@extends('layouts.admin')

@section('styles')
<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jquery-editable/css/jquery-editable.css" rel="stylesheet"/>
@endsection

@section('content')
<div class="container-fluid card">
    <div class="card-header">
        <span class="pull-left">Withdrawal Transaction</span>
        <span class="pull-right">{{$bank->bank_name}} ({{$bank->balance}})</span>
    </div>

    <div class="card-body">
        <form class="form-row" method="POST" action="{{ route("entryperson.transactions.withdrawal.store") }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="bank_id" id="bank_id" value="{{$bank->id}}" required>
            <div class="form-group col-md-3">
                <label class="required" for="customer_name">Beneficiary Name</label>
                <input class="form-control {{ $errors->has('customer_name') ? 'is-invalid' : '' }}" type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', '') }}" required>
                @if($errors->has('customer_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('customer_name') }}
                    </div>
                @endif
            </div>
            <div class="form-group col-md-2">
                <label class="required" for="amount">Amount</label>
                <input class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="number" name="amount" id="amount" value="{{ old('amount', '') }}" step="0.0001" required>
                @if($errors->has('amount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('amount') }}
                    </div>
                @endif
            </div>
            <div class="form-group col-md-3">
                <label class="required" for="beneficiary_bank">Beneficiary Bank</label>
                <input class="form-control {{ $errors->has('beneficiary_bank') ? 'is-invalid' : '' }}" type="text" name="beneficiary_bank" id="beneficiary_bank" value="{{ old('beneficiary_bank', '') }}" required>
                @if($errors->has('beneficiary_bank'))
                    <div class="invalid-feedback">
                        {{ $errors->first('beneficiary_bank') }}
                    </div>
                @endif
            </div>
            <div class="form-group col-md-3">
                <label for="remarks">Purpose</label>
                <input class="form-control {{ $errors->has('remarks') ? 'is-invalid' : '' }}" type="text" name="remarks" id="remarks" value="{{ old('remarks', '') }}">
                @if($errors->has('remarks'))
                    <div class="invalid-feedback">
                        {{ $errors->first('remarks') }}
                    </div>
                @endif
            </div>
            <div class="form-group col-md-1 text-center d-flex">
                <button class="btn btn-danger align-self-end" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Latest Transactions
    </div>
    <div class="card-body">
        <div class="row">
            <table class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Beneficiary Bank</th>
                        <th>Purpose</th>
                    </tr>
                </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                <td>
                                    <a href="" class="update" data-name="customer_name" data-type="text" data-pk="{{ $transaction->id }}" data-title="Name" required>
                                        {{ $transaction->customer_name }}
                                    </a>
                                </td>
                                <td>
                                    <a href="" class="update" data-name="amount" data-type="number" data-pk="{{ $transaction->id }}" data-title="amount" required>
                                        {{ $transaction->amount }}
                                    </a>
                                </td>
                                <td>
                                    <a href="" class="update" data-name="beneficiary_bank" data-type="text" data-pk="{{ $transaction->id }}" data-title="beneficiary_bank" required>
                                        {{ $transaction->beneficiary_bank }}
                                    </a>
                                </td>
                                <td>
                                    <a href="" class="update" data-name="remarks" data-type="text" data-pk="{{ $transaction->id }}" data-title="remarks" required>
                                        {{ $transaction->remarks }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>$.fn.poshytip={defaults:null}</script>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jquery-editable/js/jquery-editable-poshytip.min.js"></script>

<script type="text/javascript">
    $.fn.editable.defaults.mode = 'inline';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{csrf_token()}}'
        }
    });

    $('.update').editable({
        url: "{{ route('entryperson.transactions.withdrawal.update') }}",
        validate: function(value) {
        if($.trim(value) == '') {
                return 'Enter a value';
            }
        }
    });
</script>
@endsection
