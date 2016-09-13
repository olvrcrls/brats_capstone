@extends('layout')
@section('head')
	<link rel="stylesheet" href="/materialize/css/datepicker.css">
    <script type='text/javascript' src='/js/datepicker.js'></script>
	<script type="text/javascript" src="/js/index/app.js"></script>
@stop
@section('content')
	<div class="brats-border">
		<div class="row col s12 col m12">
			<h2><b>Manage Booked Trips <i class="fa fa-briefcase"></i></b></h2>
		</div>
		@if (isset($status))
		<div class="row">
			<div class="white-text card red darken-1 z-depth-2 col s4 col m4">
				<p><b>
					<i class="fa fa-remove"></i> {{ $status }}
				</b></p>
			</div>
		</div>
		@elseif (isset($successCancellation))
		<div class="row">
			<div class="white-text card green darken-1 z-depth-2 col s4 col m4">
				<p><b>
					<i class="fa fa-check"></i> {{ $successCancellation }}
				</b></p>
			</div>
		</div>
		@endif
		<div class="row">
			<div class="card grey lighten-3 accent-1 z-depth-3 col s4 col m4">
			<br>
				<form method="POST" accept-charset="utf-8" action="{{ url('/manage/trip/retrieve') }}" autocomplete="off">
					{{ csrf_field() }}
					{{ method_field('POST') }}
					<div class="row">
						<div class="input-field col s11 col m11">
							<input required type="text" name="purchaseReference" id="purchaseReference" title="Enter your transaction/purchase number"
							class="validate">
							<label for="purchaseReference">Transaction Number Reference* :</label>
						</div>
					</div>
					<div class="row">
						<div class="input-field col s11 col m11">
							<input required type="text" name="purchaseLastName" id="purchaseLastName" title="Enter online customer's last name" class="validate" pattern="[A-Za-z ,.'-]{2,150}">
							<label for="purchaseLastName">Online Customer's Surname* :</label>
						</div>
					</div>
					<div class="row">
						<div class="input-field col s11 col m11">
							<select required id="requests" name="purchaseRequest" title="Select reason for requesting your transaction">
								<option value="" disabled="" selected="">Requested Action *</option>
								<option value="voucher">Request E-Voucher</option>
								<option value="check">Request to Check Booked Transaction</option>
								<option value="cancel">Request to Cancel Booked Transaction</option>
							</select>
							<label for="requests">Actions: </label>
						</div>
					</div>
					<div class="row">
						<div class="col s5 col m5">
							<button type="submit" class="btn waves-effect waves-light amber accent-3"><b>Submit <i class="fa fa-send"></i></b></button>
						</div>
					</div>
				</form>
			</div>
			<div class="col s6 col m6 offset-m1 offset-s1">
				<h4 class="flow-text"><b><u>Important Reminders </u><i class="fa fa-info-circle"></i></b></h4>
				<ul>
					<li>
						- Enter your Transaction Number from the given E-Voucher or from the E-mail.
					</li><br>
					<li>
						- Enter the customer or the payor's surname for the transaction of the booked trip.
					</li><br>
					<li>
						- Select your request of managing your booked trip(s).
					</li><br>
					<li>
						- Applicable for the tickets that are purchased or reserved online thru the BRATS website (www.brats.com.ph) only.
					</li><br>
					<li>
						- No downgrading of fares is allowed. 	
					</li><br>
					<li>
						- Expiration dates of E-Vouchers can not be prolonged.
					</li><br>
					<li>
						- Cancellation can not be done, the day before the trip.
					</li> <br>
					<li>
						- Any other concerns, please contact landline number: 123-45-67 or 0922-222-2222 <br>
						  or e-mail to us at bratscapstone@gmail.com
					</li>
				</ul>
			</div>
		</div>
	</div>
@stop

@section('footer')
	<script type="text/javascript">
		$(document).ready(function () {
			$("#routes").material_select();
			$("#requests").material_select();
		});
	</script>
	<script type='text/javascript' src='/js/site.js'></script>
@stop