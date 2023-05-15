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
                <label>{{ trans('cruds.timing.fields.shift') }}</label>
                @foreach(App\Models\Timing::SHIFT_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('shift') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="shift_{{ $key }}" name="shift" value="{{ $key }}" {{ old('shift', '') === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="shift_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('shift'))
                    <div class="invalid-feedback">
                        {{ $errors->first('shift') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.timing.fields.shift_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="form">{{ trans('cruds.timing.fields.form') }}</label>
                <input class="form-control timepicker {{ $errors->has('form') ? 'is-invalid' : '' }}" type="text" name="form" id="form" value="{{ old('form') }}">
                @if($errors->has('form'))
                    <div class="invalid-feedback">
                        {{ $errors->first('form') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.timing.fields.form_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="to">{{ trans('cruds.timing.fields.to') }}</label>
                <input class="form-control timepicker {{ $errors->has('to') ? 'is-invalid' : '' }}" type="text" name="to" id="to" value="{{ old('to') }}">
                @if($errors->has('to'))
                    <div class="invalid-feedback">
                        {{ $errors->first('to') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.timing.fields.to_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="before_from">{{ trans('cruds.timing.fields.before_from') }}</label>
                <input class="form-control timepicker {{ $errors->has('before_from') ? 'is-invalid' : '' }}" type="text" name="before_from" id="before_from" value="{{ old('before_from') }}">
                @if($errors->has('before_from'))
                    <div class="invalid-feedback">
                        {{ $errors->first('before_from') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.timing.fields.before_from_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="before_to">{{ trans('cruds.timing.fields.before_to') }}</label>
                <input class="form-control timepicker {{ $errors->has('before_to') ? 'is-invalid' : '' }}" type="text" name="before_to" id="before_to" value="{{ old('before_to') }}">
                @if($errors->has('before_to'))
                    <div class="invalid-feedback">
                        {{ $errors->first('before_to') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.timing.fields.before_to_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="after_from">{{ trans('cruds.timing.fields.after_from') }}</label>
                <input class="form-control timepicker {{ $errors->has('after_from') ? 'is-invalid' : '' }}" type="text" name="after_from" id="after_from" value="{{ old('after_from') }}">
                @if($errors->has('after_from'))
                    <div class="invalid-feedback">
                        {{ $errors->first('after_from') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.timing.fields.after_from_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="after_to">{{ trans('cruds.timing.fields.after_to') }}</label>
                <input class="form-control timepicker {{ $errors->has('after_to') ? 'is-invalid' : '' }}" type="text" name="after_to" id="after_to" value="{{ old('after_to') }}">
                @if($errors->has('after_to'))
                    <div class="invalid-feedback">
                        {{ $errors->first('after_to') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.timing.fields.after_to_helper') }}</span>
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