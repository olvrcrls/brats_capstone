@extends('layout')
@section('content')
	<div class="brats-border">
		&nbsp;
		<div class="row col s12 col m12">
			<div class="col s12 col m12">
				<center>
					<h2>{{ $trip->route }} <i class="fa fa-bus"></i></h2>
					<h5><u>Seating Instructions:</u></h5>
				</center>
				<div class="card-panel yellow darken-2 white-text z-depth-2">
					<ul class="flow-text">
						<li>
							1. Click the seat(s) you want to reserve until the <b>number of passengers left</b> is equal to 0.
						</li>
						<li>
							2. Click the <b class="green-text"><u>PROCEED <i class="fa fa-check"></i></u></b> button to proceed and purchase your reservations.
						</li>
						<li>&nbsp;
							<p>Tip: If you made a mistake to your seating arrangement. Just click the chosen seat again to remove the person on the chair.</p>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="row col s12 col m12">
			<h5 class="flow-text center"><b>Bus Seating Arrangement</b></h5>
			<div class="row col s6 col m6">
				<span class="flow-text">Number of passengers left: <span class="red-text">@{{ passengers_left }}</span> </span>
				<input type="hidden" id="totalPassengers" v-model="passengers_left" value="{{ $trip->totalPassengers }}">
			</div>
			<div class="col s12 col m12">
				<form name="checkOutForm" method="POST" action="" accept-charset="utf-8" autocomplete="off"
					@submit.prevent
				>
					{{ csrf_field() }}	
					{{ method_field('POST') }}
					<input type="hidden" name="bus" value="{{ $trip->bus }}" v-model="SeatProfile.bus">
					<input type="hidden" name="bustype_id" value="{{ $trip->bustype_id }}" v-model="SeatProfile.bustype_id">
					<input type="hidden" name="dispatch" id="dispatch" value="{{ $trip->dispatch }}" v-model="SeatProfile.dispatch">
					<input type="hidden" name="travel_date" value="{{ $trip->travel_date }}" v-model="SeatProfile.travel_date">
					<input type="hidden" name="travel_time" value="{{ $trip->travel_time }}" v-model="SeatProfile.travel_time">
					<input type="hidden" name="route" value="{{ $trip->route }}" v-model="SeatProfile.route">
					<input type="hidden" name="route_id" value="{{ $trip->route_id }}" v-model="SeatProfile.route_id">

					<!-- DISPLAYING SEATING ARRANGEMENT -->
					<table id="seat_table" class="bordered amber lighten-3">
							
					</table>
				</form>
			</div>
		</div>
	</div>
@stop

@section('footer')
<script type="text/javascript">
	$(document).ready(function () {
		$.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
			$('select').material_select('destroy');
			$('select').material_select();
			// $("select[required]").css({display: "inline", height: 0, padding: 0, width: 0});
		});
	</script>
	<!-- <script type="text/javascript" src="/js/compiled/seats_es6.ajax.js"></script> -->
	<script type="text/javascript" src="/js/compiled/seats_es6.js"></script>
@stop