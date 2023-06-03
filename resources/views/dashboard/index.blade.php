@extends('layouts.simple.master')

@section('title', 'Default')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/chartist.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Default</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Dashboard</li>
<li class="breadcrumb-item active">Default</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row second-chart-list third-news-update">
		<div class="col-xl-4 xl-50 appointment-sec box-col-6">
			<div class="row">
				<div class="col-xl-12 appointment">
					<div class="card">
						<div class="card-header card-no-border">
							<div class="header-top">
								<h5 class="m-0">All User</h5>	
							</div>
						</div>
						<div class="card-body pt-0">
							<div class="appointment-table table-responsive">
								<table class="table table-bordernone">
									<tbody>
										@foreach(App\Models\User::all() as $user)
											@if($user->role_id == 2)
												<tr>
													<td>
														<img class="img-fluid img-40 rounded-circle mb-3" src="{{asset('assets/images/appointment/app-ent.jpg')}}" alt="Image description">
														<div class="status-circle bg-primary"></div>
													</td>
													<td class="img-content-box"><span class="d-block">{{ $user->name }}</span><span class="font-roboto">Now</span></td>
													<td>
														<p class="m-0 font-primary">{{$user->created_at}}</p>
													</td>
													<td class="text-end">
														<div class="button btn btn-primary">Done<i class="fa fa-check-circle ms-2"></i></div>
													</td>
												</tr>
											@endif
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				

			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var session_layout = '{{ session()->get('layout') }}';
</script>
@endsection

@section('script')
<script src="{{asset('assets/js/chart/chartist/chartist.js')}}"></script>
<script src="{{asset('assets/js/chart/chartist/chartist-plugin-tooltip.js')}}"></script>
<script src="{{asset('assets/js/chart/knob/knob.min.js')}}"></script>
<script src="{{asset('assets/js/chart/knob/knob-chart.js')}}"></script>
<script src="{{asset('assets/js/chart/apex-chart/apex-chart.js')}}"></script>
<script src="{{asset('assets/js/chart/apex-chart/stock-prices.js')}}"></script>
<script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
<script src="{{asset('assets/js/dashboard/default.js')}}"></script>
<script src="{{asset('assets/js/notify/index.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
<script src="{{asset('assets/js/typeahead/handlebars.js')}}"></script>
<script src="{{asset('assets/js/typeahead/typeahead.bundle.js')}}"></script>
<script src="{{asset('assets/js/typeahead/typeahead.custom.js')}}"></script>
<script src="{{asset('assets/js/typeahead-search/handlebars.js')}}"></script>
<script src="{{asset('assets/js/typeahead-search/typeahead-custom.js')}}"></script>
@endsection


{{-- <tbody>
	@foreach(App\Models\User::all() as $user)
		@if($user->hasRole('user'))
	<tr>
		<td>
			<img class="img-fluid img-40 rounded-circle mb-3" src="{{asset('assets/images/appointment/app-ent.jpg')}}" alt="Image description">
			<div class="status-circle bg-primary"></div>
		</td>
		<td class="img-content-box"><span class="d-block">{{ $user->name }}</span><span class="font-roboto">Now</span></td>
		<td>
			<p class="m-0 font-primary">{{$user->created_at}}</p>
		</td>
		<td class="text-end">
			<div class="button btn btn-primary">Done<i class="fa fa-check-circle ms-2"></i></div>
		</td>
	</tr>
	@endif
	@endforeach
</tbody>


<tbody>
	@foreach(App\Models\User::all() as $user)
		@if($user->hasRole('user'))
			<tr>
				<td>
					<img class="img-fluid img-40 rounded-circle mb-3" src="{{ asset('assets/images/appointment/app-ent.jpg') }}" alt="Image description">
					<div class="status-circle bg-primary"></div>
				</td>
				<td class="img-content-box">
					<span class="d-block">{{ $user->name }}</span>
					<br>
				</td>
				<td>
					<p class="m-0 font-primary">{{$user->created_at}}</p>
				</td>
				<td class="text-end">
					<div class="button btn btn-primary">Search History</div>
				</td>
			</tr>
		@endif
	@endforeach
</tbody> --}}