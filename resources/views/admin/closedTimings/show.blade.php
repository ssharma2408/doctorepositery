@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.closedTiming.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.closed-timings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.closedTiming.fields.id') }}
                        </th>
                        <td>
                            {{ $closedTiming->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.closedTiming.fields.user') }}
                        </th>
                        <td>
                            {{ $closedTiming->user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.closedTiming.fields.full_partial') }}
                        </th>
                        <td>
                            {{ App\Models\ClosedTiming::FULL_PARTIAL_RADIO[$closedTiming->full_partial] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.closedTiming.fields.closed_on') }}
                        </th>
                        <td>
                            {{ $closedTiming->closed_on }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.closedTiming.fields.from') }}
                        </th>
                        <td>
                            {{ $closedTiming->from }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.closedTiming.fields.to') }}
                        </th>
                        <td>
                            {{ $closedTiming->to }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.closed-timings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection