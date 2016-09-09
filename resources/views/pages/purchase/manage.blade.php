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
		<div class="row col s12 col m12">
			<form method="POST" accept-charset="utf-8" action="{{ url('/manage/trip/retrieve') }}" autocomplete="off">
				{{ csrf_field() }}
				{{ method_field('POST') }}
				<div class="row">
					<div class="input-field col s3 col m3">
						<input type="text" name="purchaseReference" id="purchaseReference" title="Enter your transaction/purchase number"
						class="validate">
						<label for="purchaseReference">Transaction Reference Number* :</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s3 col m3">
						<input type="text" name="purchaseLastName" id="purchaseLastName" title="Enter online customer's last name" class="validate">
						<label for="purchaseLastName">Online Customer's Surname* :</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s3 col m3">
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