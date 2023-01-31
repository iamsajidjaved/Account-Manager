@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        @if(isset($banks))
            @foreach  ($banks as $bank)
            <div class="col-md-3">
                <div class="card text-dark">
                    <div class="card-header text-center">
                        <i class="fa-fw fas fa-university fa-7x"></i>
                        <h6 class="mt-2">{{$bank->bank_name}}</h6>
                    </div>
                    <div class="card-body row text-center">
                        <div class="col">
                            <a href="{{ route('entryperson.transactions.deposit.create', $bank->id) }}" class="text-decoration-none">
                                <div class="text-uppercase text-muted small">Deposit</div>
                            </a>
                        </div>
                        <div class="vr"></div>
                        <div class="col">
                            <a href="{{ route('entryperson.transactions.withdrawal.create', $bank->id) }}" class="text-decoration-none">
                                <div class="text-uppercase text-muted small">Withdrawal</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
