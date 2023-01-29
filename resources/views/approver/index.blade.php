@extends('layouts.admin')

@section('styles')
<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jquery-editable/css/jquery-editable.css" rel="stylesheet"/>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        Pending Transactions
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-transactions">
                <thead>
                    <tr>
                        <th>
                            ID
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Amount
                        </th>
                        <th>
                            Reference
                        </th>
                        <th>
                            Deposit ID
                        </th>
                        <th>
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                        <tr data-entry-id="{{ $transaction->id }}">
                            <td>
                                {{ $transaction->id ?? '' }}
                            </td>
                            <td>
                                {{ $transaction->customer_name ?? '' }}
                            </td>
                            <td>
                                {{ $transaction->amount ?? '' }}
                            </td>
                            <td>
                                {{ $transaction->reference ?? '' }}
                            </td>
                            <td>
                                <a href="" class="update" data-name="deposit_no" data-type="text" data-pk="{{ $transaction->id }}" data-title="Deposit ID">
                                    {{ $transaction->deposit_no ?? '' }}
                                </a>
                            </td>
                            <td>
                                @if ($transaction->status=='Pending')
                                    <a href="#" class="status" data-name="status" data-type="select" data-pk="{{ $transaction->id }}" data-title="Deposit ID">
                                        {{ $transaction->status ?? '' }}
                                    </a>
                                @else
                                    {{ $transaction->status ?? '' }}
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
        url: "{{ route('approver.transactions.update') }}",
        validate: function(value) {
        if($.trim(value) == '') {
                return 'Enter a value';
            }
        }
    });
</script>
<script>
$(function(){
    $('.status').editable({
        url: "{{ route('approver.transactions.update') }}",
        value: 'Pending',
        source: [
              {value: 'Pending', text: 'Pending'},
              {value: 'Approved', text: 'Approved'},
              {value: 'Void', text: 'Void'}
        ],
        success: function(response, newValue) {
            if(response.success==false) {
                alert('Please fill the deposit ID');
            }
        }
    });
});
</script>
<script>
$(document).ready( function () {
    $('.datatable-transactions').DataTable();
} );
</script>
@endsection
