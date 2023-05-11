@extends('layouts.admin')
@section('content')
@can('staff_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.staffs.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.staff.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.staff.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Staff">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.staff.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.staff.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.staff.fields.email') }}
                        </th>
                        <th>
                            {{ trans('cruds.staff.fields.mobile_number') }}
                        </th>
                        <th>
                            {{ trans('cruds.staff.fields.username') }}
                        </th>
                        <th>
                            {{ trans('cruds.staff.fields.clinic') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staffs as $key => $staff)
                        <tr data-entry-id="{{ $staff->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $staff->id ?? '' }}
                            </td>
                            <td>
                                {{ $staff->name ?? '' }}
                            </td>
                            <td>
                                {{ $staff->email ?? '' }}
                            </td>
                            <td>
                                {{ $staff->mobile_number ?? '' }}
                            </td>
                            <td>
                                {{ $staff->username ?? '' }}
                            </td>
                            <td>
                                {{ $staff->clinic->name ?? '' }}
                            </td>
                            <td>
                                @can('staff_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.staffs.show', $staff->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('staff_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.staffs.edit', $staff->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('staff_delete')
                                    <form action="{{ route('admin.staffs.destroy', $staff->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
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
@can('staff_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.staffs.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-Staff:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection