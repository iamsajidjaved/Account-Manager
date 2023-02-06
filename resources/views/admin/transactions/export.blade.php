@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Export Excel Sheet
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.transactions.process-export") }}">
            @csrf
            <div class="form-group">
                <label class="required" for="bank_id">Banks</label>
                <select class="form-control select2" name="bank_id" id="bank_id" required>
                    @foreach($banks as $bank)
                        <option value="{{ $bank->id }}">{{ $bank->bank_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="required" for="start">Start Date</label>
                <input class="form-control" type="date" name="start" id="start" required>
            </div>
            <div class="form-group">
                <label class="required" for="end">End Date</label>
                <input class="form-control" type="date" name="end" id="end" required>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                   Save
                </button>
            </div>
        </form>
    </div>
</div>



@endsection
