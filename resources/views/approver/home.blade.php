@extends('layouts.admin')
@section('content')
    @php
        header("Location: " . URL::to('/approver/transactions/index'), true, 302);
        exit();
    @endphp
@endsection
