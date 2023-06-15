@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.clinic.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.clinics.update", [$clinic->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.clinic.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $clinic->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="prefix">{{ trans('cruds.clinic.fields.prefix') }}</label>
                <input class="form-control {{ $errors->has('prefix') ? 'is-invalid' : '' }}" type="text" name="prefix" id="prefix" value="{{ old('prefix', $clinic->prefix) }}" required>
                @if($errors->has('prefix'))
                    <div class="invalid-feedback">
                        {{ $errors->first('prefix') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.prefix_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="address">{{ trans('cruds.clinic.fields.address') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('address') ? 'is-invalid' : '' }}" name="address" id="address">{!! old('address', $clinic->address) !!}</textarea>
                @if($errors->has('address'))
                    <div class="invalid-feedback">
                        {{ $errors->first('address') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.address_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="package_id">{{ trans('cruds.clinic.fields.package') }}</label>
                <select class="form-control select2 {{ $errors->has('package') ? 'is-invalid' : '' }}" name="package_id" id="package_id" required>
                    @foreach($packages as $id => $entry)
                        <option value="{{ $id }}" {{ (old('package_id') ? old('package_id') : $clinic->package->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('package'))
                    <div class="invalid-feedback">
                        {{ $errors->first('package') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.package_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="package_start_date">{{ trans('cruds.clinic.fields.package_start_date') }}</label>
                <input class="form-control datetime {{ $errors->has('package_start_date') ? 'is-invalid' : '' }}" type="text" name="package_start_date" id="package_start_date" value="{{ old('package_start_date', $clinic->package_start_date) }}">
                @if($errors->has('package_start_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('package_start_date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.package_start_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="package_end_date">{{ trans('cruds.clinic.fields.package_end_date') }}</label>
                <input class="form-control datetime {{ $errors->has('package_end_date') ? 'is-invalid' : '' }}" type="text" name="package_end_date" id="package_end_date" value="{{ old('package_end_date', $clinic->package_end_date) }}">
                @if($errors->has('package_end_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('package_end_date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.package_end_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="clinic_admin_id">{{ trans('cruds.clinic.fields.clinic_admin') }}</label>
                <select class="form-control select2 {{ $errors->has('clinic_admin') ? 'is-invalid' : '' }}" name="clinic_admin_id" id="clinic_admin_id">
                    @foreach($clinic_admins as $id => $entry)
                        <option value="{{ $id }}" {{ (old('clinic_admin_id') ? old('clinic_admin_id') : $clinic->clinic_admin->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('clinic_admin'))
                    <div class="invalid-feedback">
                        {{ $errors->first('clinic_admin') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.clinic_admin_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="domain_id">{{ trans('cruds.clinic.fields.domain') }}</label>
                <select class="form-control select2 {{ $errors->has('domain') ? 'is-invalid' : '' }}" name="domain_id" id="domain_id" required>
                    @foreach($domains as $id => $entry)
                        <option value="{{ $id }}" {{ (old('domain_id') ? old('domain_id') : $clinic->domain->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('domain'))
                    <div class="invalid-feedback">
                        {{ $errors->first('domain') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.domain_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="doctors">{{ trans('cruds.clinic.fields.doctor') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('doctors') ? 'is-invalid' : '' }}" name="doctors[]" id="doctors" multiple required>
                    @foreach($doctors as $id => $doctor)
                        <option value="{{ $id }}" {{ (in_array($id, old('doctors', [])) || $clinic->doctors->contains($id)) ? 'selected' : '' }}>{{ $doctor }}</option>
                    @endforeach
                </select>
                @if($errors->has('doctors'))
                    <div class="invalid-feedback">
                        {{ $errors->first('doctors') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.doctor_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.clinic.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status" required>
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Clinic::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $clinic->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.clinic.fields.status_helper') }}</span>
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

@section('scripts')
<script>
    $(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('admin.clinics.storeCKEditorImages') }}', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $clinic->id ?? 0 }}');
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>

@endsection