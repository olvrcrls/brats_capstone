@extends('layout')
@section('content')
<div class="brats-border" style="padding-bottom: 100px;">
		<div class="row">
			<div class="col s8 col m8">
				<h3 class="teal-text">{{ $dispatch_schedules[0]->route }} <i class="fa fa-btn fa-map-marker"></i></h3>
			</div>
			<div class="col s4 col m4">
				<h3 class="right">
					<a class="red-text accent-2" href="{{ url('routes') }}" title="Return to Route Lists"><i class="fa fa-btn fa-sign-out"></i></a>
				</h3>
			</div>
		</div>
		<div class="row">
			<div class="col s12 col m12">
				<hr/>
			</div>
		</div>
		<table class="bordered highlight">
			<thead>
				<tr class>
					<th class="center">Bus Number</th>
					<th class="center">Bus Type</th>
					<th class="center">Bus Status</th>
					<th class="center">Routes (Origin - Destination)</th>
					<th class="center">Date of Departure</th>
					<th class="center">Time of Departure</th>
					<th class="center">No. of Passeger</th>
				</tr>
			</thead>
			<tbody>
				@if ( !isset($dispatch_schedules) || empty($dispatch_schedules) )
					<tr>
						<td class="center">
							No Available
						</td>
						<td class="center">
							No Available
						</td>
						<td class="center">
							No Available
						</td>
						<td class="center">
							No Available
						</td>
						<td class="center">
							No Available
						</td>
						<td class="center">
							No Available
						</td>
						<td class="center">
							No Available
						</td>
						<td class="center">
							No Available
						</td>
					</tr>
				@else
					@foreach($dispatch_schedules as $schedule)
					<form method="post" action="{{ url('/book_seats') }}" accept-charset="utf-8">
						{{ csrf_field() }}
						{{ method_field('POST') }}
						<input type="hidden" name="travel_date" value="{{ $schedule->travel_date }}"/>
						<input type="hidden" name="bus" value="{{ $schedule->bus }}"/>
						<input type="hidden" name="dispatch" value="{{ $schedule->TravelDispatch_Id }}"/>
						<input type="hidden" name="route" value="{{ $schedule->route }}"/>
						<input type="hidden" name="route_id" value="{{ $schedule->route_id }}"/>
						<input type="hidden" name="bustype" value="{{ $schedule->bustype }}"/>
						<input type="hidden" name="bustype_id" value="{{ $schedule->bustype_id }}"/>
						<input type="hidden" name="travel_time" value="{{ $schedule->time }}"/>
						<tr>
							<td class="center">{{ $schedule->bus }}</td>
							<td class="center">{{ $schedule->bustype }}</td>
							<td class="center">{{ $schedule->status }}</td>
							<td class="center">{{ $schedule->route }}</td>
							<td class="center">{{ date_format(date_create($schedule->travel_date), "m-d-Y") }}</td>
							<td class="center">{{ date_format(date_create($schedule->time), "h:i:s a") }}</td>
							<td class="center">
								<div class="input-field col sm1 col m1" style="width:150px; ">
									@if($schedule->seats <= 0)
											<span class="grey-text">No Available Seats</span>
										@else
									<select name="passengerNumber" id="passengerNumber" class="center browser-default">
										@for($i=0; $i<$schedule->seats; $i++)
										<option value="{{ $i + 1 }}">{{ $i + 1 }}</option>
										@endfor
									</select>
									@endif
								</div>
							</td>
							<td>
								@if ((strtolower($schedule->status) == 'on queue' || strtolower($schedule->status) == 'queue' ||
										strtolower($schedule->status) == 'available') &&
										!($schedule->seats <= 0))
										<button type="submit" class="btn btn-primary green">
											BOOK SEATS <i class="fa fa-btn fa-location-arrow"></i>
										</button>
								@else
									<span class="grey-text">
											<b><i class="fa fa-remove"></i> Online Booking Closed </b>
									</span>
								@endif
							</td>
						</tr>
					</form>
					@endforeach
				@endif
			</tbody>
		</table>
	</div><br><br>&nbsp;
@stop