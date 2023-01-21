@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.group.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.groups.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="group_name">{{ trans('cruds.group.fields.group_name') }}</label>
                <input class="form-control {{ $errors->has('group_name') ? 'is-invalid' : '' }}" type="text" name="group_name" id="group_name" value="{{ old('group_name', '') }}" required>
                @if($errors->has('group_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('group_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.group.fields.group_name_helper') }}</span>
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