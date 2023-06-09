@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.patientHistory.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.patient-histories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.patientHistory.fields.id') }}
                        </th>
                        <td>
                            {{ $patientHistory->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.patientHistory.fields.patient') }}
                        </th>
                        <td>
                            {{ $patientHistory->patient->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.patientHistory.fields.doctor') }}
                        </th>
                        <td>
                            {{ $patientHistory->doctor->mobile_number ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.patientHistory.fields.visit_date') }}
                        </th>
                        <td>
                            {{ $patientHistory->visit_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.patientHistory.fields.prescription') }}
                        </th>
                        <td>
                            {{ $patientHistory->prescription }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.patientHistory.fields.comment') }}
                        </th>
                        <td>
                            {!! $patientHistory->comment !!}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.patient-histories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection