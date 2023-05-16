@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.doctor.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.doctors.update", [$doctor->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="mobile_number">{{ trans('cruds.doctor.fields.mobile_number') }}</label>
                <input class="form-control {{ $errors->has('mobile_number') ? 'is-invalid' : '' }}" type="text" name="mobile_number" id="mobile_number" value="{{ old('mobile_number', $doctor->mobile_number) }}" required>
                @if($errors->has('mobile_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('mobile_number') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.doctor.fields.mobile_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="clinic_id">{{ trans('cruds.doctor.fields.clinic') }}</label>
                <select class="form-control select2 {{ $errors->has('clinic') ? 'is-invalid' : '' }}" name="clinic_id" id="clinic_id">
                    @foreach($clinics as $id => $entry)
                        <option value="{{ $id }}" {{ (old('clinic_id') ? old('clinic_id') : $doctor->clinic->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('clinic'))
                    <div class="invalid-feedback">
                        {{ $errors->first('clinic') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.doctor.fields.clinic_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="doctor_id">{{ trans('cruds.doctor.fields.doctor') }}</label>
                <select class="form-control select2 {{ $errors->has('doctor') ? 'is-invalid' : '' }}" name="doctor_id" id="doctor_id" required>
                    @foreach($doctors as $id => $entry)
                        <option value="{{ $id }}" {{ (old('doctor_id') ? old('doctor_id') : $doctor->doctor->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('doctor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('doctor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.doctor.fields.doctor_helper') }}</span>
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