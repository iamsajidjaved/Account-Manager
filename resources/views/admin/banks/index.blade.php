@extends('layouts.admin')
@section('content')
@can('bank_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.banks.create') }}">
                Add Bank
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        Bank List
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Bank">
                <thead>
                    <tr>
                        <th>
                            Name
                        </th>
                        <th>
                            Balance
                        </th>
                        <th>
                            Country
                        </th>
                        <th>
                            Group
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

                            <td class="text-center">
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

                                @can('bank_delete')
                                    @if ($bank->transactions_count==0)
                                        <form action="{{ route('admin.banks.destroy', $bank->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                        </form>
                                    @endif
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
        $.extend(true, $.fn.dataTable.defaults, {
            orderCellsTop: true,
            order: [[ 1, 'desc' ]],
            pageLength: 100,
        });
        let table = $('.datatable-Bank:not(.ajaxTable)').DataTable({})
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });
    })

</script>
@endsection
