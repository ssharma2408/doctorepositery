@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.announcement.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.announcements.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.announcement.fields.id') }}
                        </th>
                        <td>
                            {{ $announcement->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.announcement.fields.message') }}
                        </th>
                        <td>
                            {!! $announcement->message !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.announcement.fields.clinic') }}
                        </th>
                        <td>
                            {{ $announcement->clinic->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.announcement.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\Announcement::STATUS_SELECT[$announcement->status] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.announcements.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection