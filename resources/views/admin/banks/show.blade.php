@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Show Bank
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.banks.index') }}">
                    Back to list
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            ID
                        </th>
                        <td>
                            {{ $bank->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Name
                        </th>
                        <td>
                            {{ $bank->bank_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Balance
                        </th>
                        <td>
                            {{ $bank->balance }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Country
                        </th>
                        <td>
                            {{ $bank->country->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Group
                        </th>
                        <td>
                            {{ $bank->group->group_name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.banks.index') }}">
                    Back to list
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
