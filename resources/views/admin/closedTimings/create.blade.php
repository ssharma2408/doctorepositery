@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.closedTiming.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.closed-timings.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="user_id">{{ trans('cruds.closedTiming.fields.user') }}</label>
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
                <span class="help-block">{{ trans('cruds.closedTiming.fields.user_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.closedTiming.fields.full_partial') }}</label>
                @foreach(App\Models\ClosedTiming::FULL_PARTIAL_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('full_partial') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="full_partial_{{ $key }}" name="full_partial" value="{{ $key }}" {{ old('full_partial', '1') === (string) $key ? 'checked' : '' }} required>
                        <label class="form-check-label" for="full_partial_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('full_partial'))
                    <div class="invalid-feedback">
                        {{ $errors->first('full_partial') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.closedTiming.fields.full_partial_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="closed_on">{{ trans('cruds.closedTiming.fields.closed_on') }}</label>
                <input class="form-control date {{ $errors->has('closed_on') ? 'is-invalid' : '' }}" type="text" name="closed_on" id="closed_on" value="{{ old('closed_on') }}">
                @if($errors->has('closed_on'))
                    <div class="invalid-feedback">
                        {{ $errors->first('closed_on') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.closedTiming.fields.closed_on_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="from">{{ trans('cruds.closedTiming.fields.from') }}</label>
                <input class="form-control timepicker {{ $errors->has('from') ? 'is-invalid' : '' }}" type="text" name="from" id="from" value="{{ old('from') }}">
                @if($errors->has('from'))
                    <div class="invalid-feedback">
                        {{ $errors->first('from') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.closedTiming.fields.from_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="to">{{ trans('cruds.closedTiming.fields.to') }}</label>
                <input class="form-control timepicker {{ $errors->has('to') ? 'is-invalid' : '' }}" type="text" name="to" id="to" value="{{ old('to') }}">
                @if($errors->has('to'))
                    <div class="invalid-feedback">
                        {{ $errors->first('to') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.closedTiming.fields.to_helper') }}</span>
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