@extends('layouts.admin')
@section('content')
@can('clinic_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.clinics.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.clinic.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.clinic.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Clinic">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.clinic.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.clinic.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.clinic.fields.prefix') }}
                        </th>
                        <th>
                            {{ trans('cruds.clinic.fields.package') }}
                        </th>
                        <th>
                            {{ trans('cruds.clinic.fields.package_start_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.clinic.fields.package_end_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.clinic.fields.clinic_admin') }}
                        </th>
                        <th>
                            {{ trans('cruds.clinic.fields.domain') }}
                        </th>
                        <th>
                            {{ trans('cruds.clinic.fields.status') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clinics as $key => $clinic)
                        <tr data-entry-id="{{ $clinic->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $clinic->id ?? '' }}
                            </td>
                            <td>
                                {{ $clinic->name ?? '' }}
                            </td>
                            <td>
                                {{ $clinic->prefix ?? '' }}
                            </td>
                            <td>
                                {{ $clinic->package->package ?? '' }}
                            </td>
                            <td>
                                {{ $clinic->package_start_date ?? '' }}
                            </td>
                            <td>
                                {{ $clinic->package_end_date ?? '' }}
                            </td>
                            <td>
                                {{ $clinic->clinic_admin->name ?? '' }}
                            </td>
                            <td>
                                {{ $clinic->domain->name ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\Clinic::STATUS_SELECT[$clinic->status] ?? '' }}
                            </td>
                            <td>
                                @can('clinic_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.clinics.show', $clinic->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('clinic_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.clinics.edit', $clinic->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('clinic_delete')
                                    <form action="{{ route('admin.clinics.destroy', $clinic->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('clinic_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.clinics.massDestroy') }}",
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
  let table = $('.datatable-Clinic:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection