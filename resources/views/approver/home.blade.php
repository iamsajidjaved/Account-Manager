@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        @if(isset($country))
            <div class="col-md-3">
                <div class="card text-dark">
                    <div class="card-header text-center">
                        <i class="fa-fw fas fa-university fa-7x"></i>
                        <h6 class="mt-2">{{$country->name}}</h6>
                    </div>
                    <div class="card-body row text-center">
                        <div class="col">
                            <a href="{{ route('approver.transactions.index') }}" class="text-decoration-none">
                                <div class="text-uppercase text-muted small">Transactions</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
