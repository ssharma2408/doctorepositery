@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.timing.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.timings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.timing.fields.id') }}
                        </th>
                        <td>
                            {{ $timing->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.timing.fields.user') }}
                        </th>
                        <td>
                            {{ $timing->user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.timing.fields.day') }}
                        </th>
                        <td>
                            {{ App\Models\Timing::DAY_SELECT[$timing->day] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.timing.fields.start_hour') }}
                        </th>
                        <td>
                            {{ $timing->start_hour }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.timing.fields.end_hour') }}
                        </th>
                        <td>
                            {{ $timing->end_hour }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.timings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection