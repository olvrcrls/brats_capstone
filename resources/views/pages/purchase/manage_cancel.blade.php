@extends('layout')
@section('content')
	<div class="brats-border">
		<!-- Content here -->
		<h2><b>Manage Booked Trips <i class="fa fa-briefcase"></i></b></h2>
		<div class="row">
			<div class="col s6 col m6">
				<form method="POST" action="{{ url('manage/trip/cancel/request') }}" accept-charset="utf-8"
					  autocomplete="off" 
				>
					{{ csrf_field() }}
					<input type="hidden" name="purchaseDate" value="{{ date_format(date_create($purchase[0]->Purchase_Date), 'Y-m-d') }}">
					<input type="hidden" name="purchaseId" value="{{ $purchase[0]->Purchase_Id }}">
					<div class="card-panel grey lighten-4 col s12 col m12 z-depth-3">
						<br>
						<center>
							<span class="flow-text center"><b>Cancellation of Transaction Booked Transaction</b></span>
						</center>
						<div class="row">
							<div class="input-field col s10 col m10">
								<select id="reasonSelect" name="cancelReason" v-model="selectReason"
								 class="browser-default" 
								 v-on:click.self="selectMethod($event)">
									<option disabled selected value="">Select Reason for cancellation/refund</option>
									<option value="Emergency Reasons">Emergency Reasons</option>
									<option value="Cancellation of Plans">Cancellation of Plans</option>
									<option value="Unwilling to Pay">Unwilling to Pay</option>
									<option value="other">Other</option>
								</select>
								<div v-if="other">
									<br>
									<h5>State your reason here:</h5>
									<textarea class="materialize-textarea" name="cancelReasonText" id="cancelReason"
									placeholder="Reason to cancel/refund reservation." v-model="otherReason"
									rows="25" cols="10" required></textarea>
								</div>
							</div>
							<div class="col s4 col m4">
								&nbsp;<br><br>
								<button type="submit" class="btn yellow darken-1 waves-effect waves-light">
									<b>Submit <i class="fa fa-send"></i></b>
								</button>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="card-panel white z-depth-3 hoverable col s4 col m4 offset-s1 offset-m1">
				<!-- Trip details --><br>
					<table class="striped bordered responsive-table">
						<thead class="yellow accent-5">
							<tr>
								<th colspan="2">
									<center>
										<h5><b>Travel Details <i class="fa fa-briefcase"></i></b></h5>
									</center>
								</th>
							</tr>	
						</thead>
						<tbody>
							<tr>
								<td>
									<b>Route:</b>
								</td>
								<td>
									{{ $purchase[0]->Route_Name }}
								</td>
							</tr>
							<tr>
								<td>
									<b>Bus Number:</b>
								</td>
								<td>
									{{ $purchase[0]->Bus_Id }}
								</td>
							</tr>
							<tr>
								<td>
									<b>Bus Plate Number:</b>
								</td>
								<td>
									{{ $purchase[0]->Bus_PlateNumber }}
								</td>
							</tr>
							<tr>
								<td>
									<b>Bus Type:</b>
								</td>
								<td>
									{{ $purchase[0]->BusType_Name }}
								</td>
							</tr>
							<tr>
								<td>
									<b>Bus Status:</b>
								</td>
								<td>
									{{ $purchase[0]->BusStatus_Name }}
								</td>
							</tr>
							<tr>
								<td>
									<b>Departure Date:</b>
								</td>
								<td>
									{{ date_format(date_create($purchase[0]->TravelDispatch_Date), 'm/d/Y') }}
								</td>
							</tr>
							<tr>
								<td>
									<b>Departure Time:</b>
								</td>
								<td>
									{{ date_format(date_create($purchase[0]->TravelSchedule_Time), 'h:i:s A') }}
								</td>
							</tr>
							<tr>
								<td>
									<b>Date of Reservation:</b>
								</td>
								<td>
									{{ date_format(date_create($purchase[0]->Purchase_Date), 'm/d/Y') }}
								</td>
							</tr>
							<tr>
								<td>
									<b>Time of Reservation:</b>
								</td>
								<td>
									{{ date_format(date_create($purchase[0]->Purchase_Date), 'h:i:s A') }}
								</td>
							</tr>
							<tr>
								<td>
									<b>Number of Passengers:</b>
								</td>
								<td>
									{{ $purchase->count() }}
								</td>
							</tr>
							<tr>
								<td>
									<b>Total Transaction Price:</b>
								</td>
								<td>
									<span class="green-text">Php {{ $purchase[0]->Purchase_TotalPrice }}</span>
								</td>
							</tr>
							<tr>
								<td>
									<b>Payment Status:</b>
								</td>
								<td>
									<span class="grey-text">{{ $purchase[0]->PaymentStatus_Name }}</span>
								</td>
							</tr>
							<tr>
								<td>
									<b>Total Balance:</b>
								</td>
								<td>
									<span class="red-text">Php {{ $costLeft }}</span>
								</td>
							</tr>
						</tbody>
					</table>
					&nbsp;
			</div>
		</div>
	</div>
	<br><br>
@stop
@section('footer')
	<script type="text/javascript">
		$(document).ready(function() {
			$("#reasonSelect").material_select();
		});
	</script>
	<script type="text/javascript" src="/js/compiled/cancellation_es6.js"></script>
@stop