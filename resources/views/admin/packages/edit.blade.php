@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.package.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.packages.update", [$package->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="package">{{ trans('cruds.package.fields.package') }}</label>
                <input class="form-control {{ $errors->has('package') ? 'is-invalid' : '' }}" type="text" name="package" id="package" value="{{ old('package', $package->package) }}" required>
                @if($errors->has('package'))
                    <div class="invalid-feedback">
                        {{ $errors->first('package') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.package.fields.package_helper') }}</span>
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