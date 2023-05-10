@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.clinic.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.clinics.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.id') }}
                        </th>
                        <td>
                            {{ $clinic->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.name') }}
                        </th>
                        <td>
                            {{ $clinic->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.address') }}
                        </th>
                        <td>
                            {!! $clinic->address !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.clinic.fields.package_ids') }}
                        </th>
                        <td>
                            {{ $clinic->package_ids->package ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.clinics.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#clinic_ids_doctors" role="tab" data-toggle="tab">
                {{ trans('cruds.doctor.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="clinic_ids_doctors">
            @includeIf('admin.clinics.relationships.clinicIdsDoctors', ['doctors' => $clinic->clinicIdsDoctors])
        </div>
    </div>
</div>

@endsection