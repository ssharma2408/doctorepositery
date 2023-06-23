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
						<a href="#tab-{{ $role }}" aria-controls="#tab-{{ $role }}" role="tab" data-toggle="tab" id="{{ $role }}">{{ $role }}</a>
					</li>					
				@endforeach 
			
			</ul>

			<!-- Tab panes -->
			<div class="tab-content">

				@foreach($user_arr as $role => $userarr)					
					<div role="tabpanel" @if($role == "Clinic_Admin") class="tab-pane active" @else class="tab-pane" @endif id="tab-{{ $role }}">
						@foreach($userarr as $user)
							<div class="mt-4" id="user_div_{{$user->id}}">
								<div class="row mb-2"><div class="col-md-6"><span><b>{{ $user->name }}</b></span></div><div class="col-md-6"><span class="frm_toggle">+</span></div></div>
								<div class="form_container">
									<form method="post" action="{{route('admin.opening.hours.save')}}">
										@csrf
										@if(empty($user->timings))
											@foreach($day_arr as $key=>$day)
												<div class="row mb-4 daycontainer">
													<div class="col-md-3">
														{{ $day }}
														<input type="checkbox" id="day_{{$key+1}}" name="day_{{$key+1}}" class="check_day" />
													</div>
													<div class="timing col-md-9">
														<div class="row">
															<div class="col-md-4">
																Open at
																<input type="time" id="" name="open_{{$key+1}}[]" min="07:00" max="23:00" />
															</div>
															<div class="col-md-4">
																Close at
																<input type="time" id="" name="close_{{$key+1}}[]" min="07:00" max="23:00" />
															</div>
															<div class="col-md-2">
																Max Token
																<input type="number" id="" name="maxtoken_{{$key+1}}[]" min="0" />
															</div>
															<div class="col-md-2">
																<span class="add_row" id="add_row" data-key ="{{$key+1}}">+</span>
															</div>
														</div>													
													</div>
												</div>
											@endforeach
										@else
											@foreach($day_arr as $key=>$day)
												<div class="row mb-4 daycontainer">
													<div class="col-md-3">
														{{ $day }}
														@php
															$checked = "";
															if(!isset($user->timings[$key+1])){
																$checked = "checked";
															}
														@endphp
														<input type="checkbox" id="day_{{$key+1}}" name="day_{{$key+1}}" class="check_day" {{$checked}} />
													</div>
													<div class="timing col-md-9">
														@if(isset($user->timings[$key+1]))
															@foreach($user->timings[$key+1] as $slot=>$timing)
																<div class="row mt-2">
																	<div class="col-md-4">
																		Open at
																		<input type="time" id="" name="open_{{$key+1}}[]" min="07:00" max="23:00" value="{{$timing->start_hour}}" />
																	</div>
																	<div class="col-md-4">
																		Close at
																		<input type="time" id="" name="close_{{$key+1}}[]" min="07:00" max="23:00" value="{{$timing->end_hour}}" />
																	</div>
																	<div class="col-md-2">
																		Max Token
																		<input type="number" id="" name="maxtoken_{{$key+1}}[]" min="0" value="{{$timing->max_token}}" />
																	</div>
																	<div class="col-md-2">
																		@if($slot ==0)
																			<span class="add_row" id="add_row" data-key ="{{$key+1}}">+</span>
																		@else
																			<span class="remove_row" id="remove_row" data-key ="{{$key+1}}">-</span>
																		@endif
																	</div>
																</div>
															@endforeach
														@else
															<div class="row mt-2">
																<div class="col-md-4">
																	Open at
																	<input type="time" id="" name="open_{{$key+1}}[]" min="07:00" max="23:00" />
																</div>
																<div class="col-md-4">
																	Close at
																	<input type="time" id="" name="close_{{$key+1}}[]" min="07:00" max="23:00" />
																</div>
																<div class="col-md-2">
																	Max Token
																	<input type="number" id="" name="maxtoken_{{$key+1}}[]" min="0" />
																</div>
																<div class="col-md-2">																		
																	<span class="add_row" id="add_row" data-key ="{{$key+1}}">+</span>
																</div>
															</div>
														@endif														
													</div>
												</div>
											@endforeach
										@endif
										<button type="submit" class="btn btn-success">Save</button>
										<input type="hidden" name="user_id" value="{{ $user->id }}">
									</form>
								</div>
							</div>
						@endforeach
					</div>					
				@endforeach 
				
			</div>

		</div>
    </div>
</div>
@endsection
@section('scripts')
	<script>	
		$(".form_container").hide();
		$(".frm_toggle").click(function(){
			$(this).parents(1).next(".form_container").toggle();
		});
		$(".check_day").click(function(){		
			$(this).parent().next(".timing").toggle();			
		});
		
		$(".add_row").click(function(){
			$(this).parent().parent().parent().append(row_html($(this).data("key")));
		});
		$(".timing").on('click','.remove_row',function(){
		   $(this).parent().parent().remove();		
		});
		
		function row_html(key){
			return '<div class="row mt-2"><div class="col-md-4">Open at <input type="time" id="" name="open_'+key+'[]" min="07:00" max="23:00" /></div><div class="col-md-4">Close at <input type="time" id="" name="close_'+key+'[]" min="07:00" max="23:00" /></div><div class="col-md-2">Max Token<input type="number" id="" name="maxtoken_'+key+'[]" min="0" /></div><div class="col-md-2"><span class="remove_row" id="remove_row">-</span></div></div>';	
		}
		$(function() {
			$(".check_day").each(function(){
				if($(this).prop('checked') == true){
					$(this).parent().next(".timing").toggle();
				}
			});
			<?php
				if($user_id != "" && $use_role != ""){					
					?>
					$("#<?php echo $use_role; ?>").trigger("click");
					$("#user_div_<?php echo $user_id;?>").find(".frm_toggle").trigger("click");
					<?php
				}
			?>
		});
	</script>
@endsection