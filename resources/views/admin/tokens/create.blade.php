@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.token.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.tokens.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="token_number">{{ trans('cruds.token.fields.token_number') }}</label>
                <input class="form-control {{ $errors->has('token_number') ? 'is-invalid' : '' }}" type="number" name="token_number" id="token_number" value="{{ old('token_number', '') }}" step="1" required>
                @if($errors->has('token_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('token_number') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.token.fields.token_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="clinic_id">{{ trans('cruds.token.fields.clinic') }}</label>
                <select class="form-control select2 {{ $errors->has('clinic') ? 'is-invalid' : '' }}" name="clinic_id" id="clinic_id" required>
                    @foreach($clinics as $id => $entry)
                        <option value="{{ $id }}" {{ old('clinic_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('clinic'))
                    <div class="invalid-feedback">
                        {{ $errors->first('clinic') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.token.fields.clinic_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="patient_id">{{ trans('cruds.token.fields.patient') }}</label>
                <select class="form-control select2 {{ $errors->has('patient') ? 'is-invalid' : '' }}" name="patient_id" id="patient_id" required>
                    @foreach($patients as $id => $entry)
                        <option value="{{ $id }}" {{ old('patient_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('patient'))
                    <div class="invalid-feedback">
                        {{ $errors->first('patient') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.token.fields.patient_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="doctor_id">{{ trans('cruds.token.fields.doctor') }}</label>
                <select class="form-control select2 {{ $errors->has('doctor') ? 'is-invalid' : '' }}" name="doctor_id" id="doctor_id" required>
                    @foreach($doctors as $id => $entry)
                        <option value="{{ $id }}" {{ old('doctor_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('doctor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('doctor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.token.fields.doctor_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="estimated_time">{{ trans('cruds.token.fields.estimated_time') }}</label>
                <input class="form-control {{ $errors->has('estimated_time') ? 'is-invalid' : '' }}" type="text" name="estimated_time" id="estimated_time" value="{{ old('estimated_time', '') }}" required>
                @if($errors->has('estimated_time'))
                    <div class="invalid-feedback">
                        {{ $errors->first('estimated_time') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.token.fields.estimated_time_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection