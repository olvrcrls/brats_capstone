@extends('layout')
@section('content')
	<div class="container">
		<h2>List of Routes <i class="fa fa-btn fa-road"></i></h2>
		<!-- <div class="row">
			<div class="input-field col m6 col s6">
				<input type="text" name="searchBar" id="searchBar" placeholder="Search a specific Route" />
			</div>
		</div> -->
		<table class="highlight bordered">
			<tbody>
			@if(!isset($routes) || empty($routes) || !$routes->count())
				<tr>
					<td>
						<h4 class="blue-grey-text">No Schedules Available, yet. <i class="fa fa-remove"></i></h4>
					</td>
				</tr>
			@else
				@foreach($routes as $route)
						<tr>
							<td>
								<a class="flow-text teal-text" href="{{ url('/route').'/'.$route->Route_Id }}">
								<p>
									<i class="fa fa-btn fa-map-marker"></i> {{ $route->Route_Name }} 
								</p>	
								</a>
							</td>
						</tr>
				@endforeach
			@endif
			</tbody>
		</table>
	</div><br/><br/><br/><br/>
@stop