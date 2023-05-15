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
                            {{ trans('cruds.timing.fields.shift') }}
                        </th>
                        <td>
                            {{ App\Models\Timing::SHIFT_RADIO[$timing->shift] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.timing.fields.form') }}
                        </th>
                        <td>
                            {{ $timing->form }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.timing.fields.to') }}
                        </th>
                        <td>
                            {{ $timing->to }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.timing.fields.before_from') }}
                        </th>
                        <td>
                            {{ $timing->before_from }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.timing.fields.before_to') }}
                        </th>
                        <td>
                            {{ $timing->before_to }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.timing.fields.after_from') }}
                        </th>
                        <td>
                            {{ $timing->after_from }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.timing.fields.after_to') }}
                        </th>
                        <td>
                            {{ $timing->after_to }}
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