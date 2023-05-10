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
                <label class="required" for="name">{{ trans('cruds.doctor.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $doctor->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.doctor.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="clinic_ids_id">{{ trans('cruds.doctor.fields.clinic_ids') }}</label>
                <select class="form-control select2 {{ $errors->has('clinic_ids') ? 'is-invalid' : '' }}" name="clinic_ids_id" id="clinic_ids_id">
                    @foreach($clinic_ids as $id => $entry)
                        <option value="{{ $id }}" {{ (old('clinic_ids_id') ? old('clinic_ids_id') : $doctor->clinic_ids->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('clinic_ids'))
                    <div class="invalid-feedback">
                        {{ $errors->first('clinic_ids') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.doctor.fields.clinic_ids_helper') }}</span>
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