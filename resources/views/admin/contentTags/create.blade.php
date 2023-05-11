@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.contentTag.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.content-tags.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.contentTag.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.contentTag.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="slug">{{ trans('cruds.contentTag.fields.slug') }}</label>
                <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text" name="slug" id="slug" value="{{ old('slug', '') }}">
                @if($errors->has('slug'))
                    <div class="invalid-feedback">
                        {{ $errors->first('slug') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.contentTag.fields.slug_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="clinic_ids_id">{{ trans('cruds.contentTag.fields.clinic_ids') }}</label>
                <select class="form-control select2 {{ $errors->has('clinic_ids') ? 'is-invalid' : '' }}" name="clinic_ids_id" id="clinic_ids_id" required>
                    @foreach($clinic_ids as $id => $entry)
                        <option value="{{ $id }}" {{ old('clinic_ids_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('clinic_ids'))
                    <div class="invalid-feedback">
                        {{ $errors->first('clinic_ids') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.contentTag.fields.clinic_ids_helper') }}</span>
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