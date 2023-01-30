@extends('layouts.admin')
@section('content')
@can('group_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.groups.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.group.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        Create Group
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Transaction">
                <thead>
                    <tr>
                        <th>
                            ID
                        </th>
                        <th>
                            Group Name
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groups as $key => $group)
                        <tr data-entry-id="{{ $group->id }}">
                            <td>
                                {{ $group->id ?? '' }}
                            </td>
                            <td>
                                {{ $group->group_name ?? '' }}
                            </td>
                            <td class="text-center">
                                @can('group_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.groups.show', $group->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('group_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.groups.edit', $group->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('group_delete')
                                    @if ($group->banks_count == 0)
                                        <form action="{{ route('admin.groups.destroy', $group->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
        let table = $('.datatable-Transaction:not(.ajaxTable)').DataTable({})
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });
    })
</script>
@endsection
