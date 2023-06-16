@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.timing.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.timings.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="user_id">{{ trans('cruds.timing.fields.user') }}</label>
                <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <div class="invalid-feedback">
                        {{ $errors->first('user') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.timing.fields.user_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.timing.fields.day') }}</label>
                <select class="form-control {{ $errors->has('day') ? 'is-invalid' : '' }}" name="day" id="day">
                    <option value disabled {{ old('day', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Timing::DAY_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('day', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('day'))
                    <div class="invalid-feedback">
                        {{ $errors->first('day') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.timing.fields.day_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="start_hour">{{ trans('cruds.timing.fields.start_hour') }}</label>
                <input class="form-control timepicker {{ $errors->has('start_hour') ? 'is-invalid' : '' }}" type="text" name="start_hour" id="start_hour" value="{{ old('start_hour') }}">
                @if($errors->has('start_hour'))
                    <div class="invalid-feedback">
                        {{ $errors->first('start_hour') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.timing.fields.start_hour_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="end_hour">{{ trans('cruds.timing.fields.end_hour') }}</label>
                <input class="form-control timepicker {{ $errors->has('end_hour') ? 'is-invalid' : '' }}" type="text" name="end_hour" id="end_hour" value="{{ old('end_hour') }}">
                @if($errors->has('end_hour'))
                    <div class="invalid-feedback">
                        {{ $errors->first('end_hour') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.timing.fields.end_hour_helper') }}</span>
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