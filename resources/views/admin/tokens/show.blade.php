@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.token.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.tokens.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.token.fields.id') }}
                        </th>
                        <td>
                            {{ $token->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.token.fields.token_number') }}
                        </th>
                        <td>
                            {{ $token->token_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.token.fields.clinic') }}
                        </th>
                        <td>
                            {{ $token->clinic->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.token.fields.patient') }}
                        </th>
                        <td>
                            {{ $token->patient->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.token.fields.doctor') }}
                        </th>
                        <td>
                            {{ $token->doctor->mobile_number ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.token.fields.estimated_time') }}
                        </th>
                        <td>
                            {{ $token->estimated_time }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.tokens.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection