@extends('layouts.admin')
@section('content')
@can('bank_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.banks.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.bank.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.bank.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Bank">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.bank.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.bank.fields.bank_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.bank.fields.balance') }}
                        </th>
                        <th>
                            {{ trans('cruds.bank.fields.country') }}
                        </th>
                        <th>
                            {{ trans('cruds.bank.fields.group') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($banks as $key => $bank)
                        <tr data-entry-id="{{ $bank->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $bank->id ?? '' }}
                            </td>
                            <td>
                                {{ $bank->bank_name ?? '' }}
                            </td>
                            <td>
                                {{ $bank->balance ?? '' }}
                            </td>
                            <td>
                                {{ $bank->country->name ?? '' }}
                            </td>
                            <td>
                                {{ $bank->group->group_name ?? '' }}
                            </td>
                            <td>
                                @can('bank_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.banks.show', $bank->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('bank_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.banks.edit', $bank->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan


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
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
  
  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-Bank:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection