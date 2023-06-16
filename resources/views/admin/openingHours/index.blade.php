@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.openingHour.title') }}
    </div>

    <div class="card-body">
        <div class="tabpanel">

			<!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist">

				@foreach($user_arr as $role => $userarr)					
					<li role="presentation" @if($role == "Clinic_Admin") class="active" @endif>
						<a href="#tab-{{ $role }}" aria-controls="#tab-{{ $role }}" role="tab" data-toggle="tab">{{ $role }}</a
					</li>					
				@endforeach 
			
			</ul>

			<!-- Tab panes -->
			<div class="tab-content">

				@foreach($user_arr as $role => $userarr)					
					<div role="tabpanel" @if($role == "Clinic_Admin") class="tab-pane active" @else class="tab-pane" @endif id="tab-{{ $role }}">
						@foreach($userarr as $user)
							<h2>{{ $user->name }}</h2>

							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias enim obcaecati praesentium repellat. Est explicabo facilis fuga illum iusto, obcaecati saepe voluptates! Dolores eaque porro quaerat sunt totam ut, voluptas.</p>
						@endforeach
					</div>					
				@endforeach 
				
			</div>

		</div>
    </div>
</div>



@endsection