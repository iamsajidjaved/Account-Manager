@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.bank.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.banks.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.bank.fields.id') }}
                        </th>
                        <td>
                            {{ $bank->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.bank.fields.bank_name') }}
                        </th>
                        <td>
                            {{ $bank->bank_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.bank.fields.balance') }}
                        </th>
                        <td>
                            {{ $bank->balance }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.bank.fields.country') }}
                        </th>
                        <td>
                            {{ $bank->country->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.bank.fields.group') }}
                        </th>
                        <td>
                            {{ $bank->group->group_name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.banks.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection