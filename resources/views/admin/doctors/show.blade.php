@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.doctor.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.doctors.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.doctor.fields.id') }}
                        </th>
                        <td>
                            {{ $doctor->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.doctor.fields.name') }}
                        </th>
                        <td>
                            {{ $doctor->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.doctor.fields.email') }}
                        </th>
                        <td>
                            {{ $doctor->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.doctor.fields.mobile_number') }}
                        </th>
                        <td>
                            {{ $doctor->mobile_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.doctor.fields.clinic') }}
                        </th>
                        <td>
                            {{ $doctor->clinic->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.doctors.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection