@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.closedTiming.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.closed-timings.update", [$closedTiming->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="user_id">{{ trans('cruds.closedTiming.fields.user') }}</label>
                <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ (old('user_id') ? old('user_id') : $closedTiming->user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
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
                <label for="closed_on">{{ trans('cruds.closedTiming.fields.closed_on') }}</label>
                <input class="form-control date {{ $errors->has('closed_on') ? 'is-invalid' : '' }}" type="text" name="closed_on" id="closed_on" value="{{ old('closed_on', $closedTiming->closed_on) }}">
                @if($errors->has('closed_on'))
                    <div class="invalid-feedback">
                        {{ $errors->first('closed_on') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.closedTiming.fields.closed_on_helper') }}</span>
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